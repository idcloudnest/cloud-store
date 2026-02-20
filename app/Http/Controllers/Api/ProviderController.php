<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// Models
use App\Models\Provider;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Transaction;

// Services
use App\Services\Provider\ProviderFactory;
use App\Services\TelegramService;
use App\Services\Transaction\ManualTransactionService;

class ProviderController extends Controller
{
	use \App\Traits\ApiResponser;

	protected $telegram;
	protected $refundService;

	public function __construct(ManualTransactionService $refundService, TelegramService $telegram)
	{
		$this->refundService = $refundService;
		$this->telegram = $telegram;
	}

	public function syncProduct(Request $request)
	{
		// Gunakan Transaction agar data aman (Rollback jika error di tengah jalan)
		DB::beginTransaction();
		try {
			$provider = Provider::find($request->provider_id);

			$service = ProviderFactory::make($provider->code);

			$response = $service->productList($request->type);
			// Log::debug(json_encode($response, JSON_PRETTY_PRINT));

			// 2. Validasi Response
			if (!isset($response['data']) || !is_array($response['data']))
				return $this->errorResponse('Gagal terhubung ke provider.', 502);

			if (isset($response['data']['rc']) && $response['data']['rc'] == 83)
				return $this->errorResponse($response['data']['message'], 400);

			$digiflazzData = $response['data'];
			$processedCount = 0;

			foreach ($digiflazzData as $item) {
				// Mapping kategori (Games, Pulsa, dll)
				// $dbCategory = $this->mapCategory($item['category']);

				// Buat Brand jika belum ada, atau ambil ID-nya jika sudah ada
				// Kita gunakan 'brand' sebagai nama, dan slug sebagai unique identifier
				$brandName = strtoupper($item['brand']); // Contoh: "TELKOMSEL"
				$brandSlug = Str::slug($item['brand']);  // Contoh: "telkomsel"

				$brand = Brand::firstOrCreate(
					[
						'slug' => $brandSlug,
					],
					[
						'name'      => $brandName,
						'image'     => null, // Upload manual nanti
						// 'category'  => $request->type === 'prepaid' ? 'prabayar' : 'pascabayar',
						'status'    => 1
					]
				);

				// Cek status: Aktif jika Buyer DAN Seller statusnya true
				$isActive = $item['buyer_product_status'] && $item['seller_product_status'];

				// Handle jam cut off yang kosong "" menjadi default "00:00:00"
				$startCutOff = empty($item['start_cut_off']) ? '00:00:00' : $item['start_cut_off'];
				$endCutOff   = empty($item['end_cut_off'])   ? '00:00:00' : $item['end_cut_off'];

				if ($request->type === 'prepaid') {
					$price = $item['price'];
					// $sellingPrice = $item['price'];
					$type = $item['type'] ?? 'umum';
					$commission = 0;
				} else {
					$commission = $item['commission'];
					$price = max(0, $item['admin'] - $commission);
					// $sellingPrice = $item['admin'];
					$type = 'pascabayar';
				}

				Product::updateOrCreate(
					['buyer_sku_code' => $item['buyer_sku_code']], // Cek berdasarkan SKU (Unik)
					[
						'provider_id'            => $request->provider_id,
						'brand_id'               => $brand->id,
						// 'brand'                  => $item['brand'],
						'product_name'           => $item['product_name'],
						'price'                  => $price, // Harga modal/jual dari API
						// 'selling_price'          => $sellingPrice,
						'commission'             => $commission,
						'type'                   => $type,
						'seller_name'            => $item['seller_name'],
						// 'description'            => $item['desc'] ?? '-',
						'start_cut_off'          => $startCutOff,
						'end_cut_off'            => $endCutOff,
						'status'                 => $isActive,
						'stock'                  => $item['stock'] ?? 0,
						'unlimited_stock'        => $item['unlimited_stock'] ?? true,
						'multi'                  => $item['multi'] ?? false,
						'seller_product_status'  => $item['seller_product_status'],
						'buyer_product_status'   => $item['buyer_product_status'],
					]
				);

				$processedCount++;
			}

			DB::commit();

			return $this->successResponse(message: "Berhasil sinkronisasi {$processedCount} produk dari {$provider->name}.");

		} catch (Exception $e) {
			// Jika ada error, batalkan semua perubahan DB
			DB::rollBack();

			Log::error("FAILED TO SYNC PRODUCT PROVIDER", $e->getMessage());

			return $this->errorResponse('Internal server error!', 500);
		}
	}

	public function checkUsername(Request $request)
	{
		$category = $request->category;
		$service = ProviderFactory::make($category == 'games' ? 'vipayment' : 'digiflazz');
		$response = $category == 'games'
			? $service->checkUsername($request->code_game, $request->user_id, $request->server_id)
			: $service->checkPlnId($request->target);

		if (!isset($response['data']) || empty($response['data']))
			return $this->errorResponse('Gagal terhubung ke provider', 502);

		if (isset($response['data']['rc']) && $response['data']['rc'] === '02')
			return $this->errorResponse('ID pelanggan tidak ditemukan', 400);

		if (isset($response['result']) && !$response['result'])
			return $this->errorResponse('ID pelanggan tidak ditemukan', 400);

		return $this->successResponse($response['data'], message: 'Ok');
	}

	public function resendJob(Request $request)
	{
		try {
			$transaction = Transaction::with(['user:id,name,phone','product:id,type'])->where([
				['id', '=', $request->id],
				['payment_status', '=', 'paid'],
				['delivery_status', '=', 'failed'],
			])->first();

			if (!$transaction)
				return $this->errorResponse('Transaksi dengan status (failed) tidak ditemukan', 404);

			$commands = '';
			if (isset($transaction->product->type) && strtolower($transaction->product->type) === 'pascabayar') {
				$commands = 'status-pasca';
			}

			$customerNo = trim($transaction->customer_no . $transaction->zone_id);

			$service = ProviderFactory::make('digiflazz');

			// --- HIT API ---
			// Untuk Prabayar: Kirim ulang RefID yg sama = Cek Status (Idempotent)
			// Untuk Pascabayar: Kirim commands 'status-pasca'
			$response = $service->transaction(
				refId: $transaction->invoice,
				skuCode: $transaction->sku_snapshot,
				destination: $customerNo,
				commands: $commands,
			);

			$data = $response['data'] ?? null;

			if ($data) {
				if ($data['rc'] === '00') {
					$transaction->update([
						'delivery_status'  => 'success',
						'sn'               => $data['sn'],
						'provider_message' => $data['message'] ?? 'Transaksi Sukses',
					]);
					// Log::info("SCHEDULER: TRX {$transaction->invoice} Updated to SUCCESS");
				}
				elseif ($data['rc'] === '03') {
					// Tidak perlu update DB jika status sudah 'processing',
					// atau update timestamp 'updated_at' untuk tanda masih dipantau
					// Log::info("SCHEDULER: TRX {$transaction->invoice} Still Pending...");
					$this->telegram->sendTransactionError($transaction, $data['message'] ?? "SCHEDULER: TRX {$transaction->invoice} Still Pending...");
				}
				else {
					$failReason = $data['message'] ?? 'Gagal dari Provider';

					$this->telegram->sendTransactionError($transaction, $failReason);

					$this->refundService->processRefund($transaction, $transaction->total_amount, $failReason);
				}
			}

			return $this->successResponse(message: 'Transaksi berhasil dikirim ulang ke antrian!');
		} catch (\Throwable $th) {
			Log::error('RE-SEND JOB ERROR', ['message' => $th->getMessage()]);
			return $this->errorResponse('Internal server error!');
		}
	}
}
