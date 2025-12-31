<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Digiflazz\DigiflazzService;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class DigiflazzController extends Controller
{
	protected DigiflazzService $digiflazz;

	public function __construct(
		DigiflazzService $digiflazz
	)
	{
		$this->digiflazz = $digiflazz;
	}

	public function saldo()
	{
		return response()->json(
			$this->digiflazz->checkBalance()
		);
	}

	public function produk()
	{
		return response()->json(
			$this->digiflazz->productList()
		);
	}

	public function transaksi(Request $request)
	{

		// $request->validate([
		// 	'sku' => 'required|string',
		// 	'customer_no' => 'required|string',
		// ]);

		$refId = 'trx_' . time();

		$buyerSkuCode = 'buyer-sku-code';
		$customerNo = 'customer-no';

		return response()->json(
			$this->digiflazz->transaction(
				refId: $refId,
				buyerSkuCode: $buyerSkuCode,
				customerNo: $customerNo,
				maxPrice: 500
			)
		);
	}

	public function sync()
	{
		// Gunakan Transaction agar data aman (Rollback jika error di tengah jalan)
		DB::beginTransaction();

		try {
			// 1. Panggil API ke Digiflazz
			$response = $this->digiflazz->productList();

			\Log::debug(json_encode($response, JSON_PRETTY_PRINT));

			// 2. Validasi Response
			if (!isset($response['data']) || !is_array($response['data'])) {
				throw new Exception('Format respon API tidak valid atau data kosong.');
			}

			if (isset($response['data']['rc']) && $response['data']['rc'] == 83)
				throw new Exception($response['data']['message']);

			$digiflazzData = $response['data'];
			$processedCount = 0;

			foreach ($digiflazzData as $item) {

				// --- LOGIC 1: BRAND / KATEGORI (PARENT) ---

				// Mapping kategori (Games, Pulsa, dll)
				$dbCategory = $this->mapCategory($item['category']);

				// Buat Brand jika belum ada, atau ambil ID-nya jika sudah ada
				// Kita gunakan 'brand' sebagai nama, dan slug sebagai unique identifier
				$brandName = strtoupper($item['brand']); // Contoh: "TELKOMSEL"
				$brandSlug = Str::slug($item['brand']);  // Contoh: "telkomsel"

				$brand = Brand::firstOrCreate(
					[
						'slug' => $brandSlug,
						'category'  => $dbCategory,
					],
					[
						'name'      => $brandName,
						'image'     => null, // Upload manual nanti
						'status'    => 1
					]
				);

				// --- LOGIC 2: PRODUK (CHILD) ---

				// Cek status: Aktif jika Buyer DAN Seller statusnya true
				$isActive = $item['buyer_product_status'] && $item['seller_product_status'];

				// Handle jam cut off yang kosong "" menjadi default "00:00:00"
				$startCutOff = empty($item['start_cut_off']) ? '00:00:00' : $item['start_cut_off'];
				$endCutOff   = empty($item['end_cut_off'])   ? '00:00:00' : $item['end_cut_off'];

				Product::updateOrCreate(
					['buyer_sku_code' => $item['buyer_sku_code']], // Cek berdasarkan SKU (Unik)
					[
						'brand_id'               => $brand->id, // Sambungkan ke Brand ID di atas
						'brand'                  => $item['brand'],
						'product_name'           => $item['product_name'],
						'price'                  => $item['price'], // Harga modal/jual dari API
						'type'                   => $item['type'] ?? 'Umum',
						'seller_name'            => $item['seller_name'],
						'description'            => $item['desc'],
						'start_cut_off'          => $startCutOff,
						'end_cut_off'            => $endCutOff,
						'status'                 => $isActive,
						'stock'                  => $item['stock'],
						'unlimited_stock'        => $item['unlimited_stock'],
						'multi'                  => $item['multi'],
						'category'               => strtolower($item['category']),
						'seller_product_status'  => $item['seller_product_status'],
						'buyer_product_status'   => $item['buyer_product_status'],
					]
				);

				$processedCount++;
			}

			// Jika semua lancar, simpan ke database permanen
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => "Berhasil sinkronisasi {$processedCount} produk dari Digiflazz.",
			]);

		} catch (Exception $e) {
			// Jika ada error, batalkan semua perubahan DB
			DB::rollBack();

			return response()->json([
				'success' => false,
				'message' => 'Gagal sinkronisasi: ' . $e->getMessage(),
				'line'    => $e->getLine(),
				'file'    => $e->getFile()
			], 500);
		}
	}

	/**
	* Helper: Ubah kategori Digiflazz jadi kategori simpel Database kita
	*/
	private function mapCategory($apiCategory)
	{
		$cat = strtolower($apiCategory);

		if (str_contains($cat, 'game')) return 'games';
		if (str_contains($cat, 'pulsa')) return 'pulsa';
		if (str_contains($cat, 'data')) return 'data';
		if (
			str_contains($cat, 'masa aktif') ||
			str_contains($cat, 'masaaktif') ||
			str_contains($cat, 'active')
		) {
			return 'masa aktif';
		}
		if (str_contains($cat, 'voucher')) return 'voucher';
		if (str_contains($cat, 'streaming')) return 'streaming';
		if (
			str_contains($cat, 'e-money') ||
			str_contains($cat, 'e money') ||
			str_contains($cat, 'emoney') ||
			str_contains($cat, 'ewallet') ||
			str_contains($cat, 'gopay') ||
			str_contains($cat, 'ovo') ||
			str_contains($cat, 'dana') ||
			str_contains($cat, 'tapcash') ||
			str_contains($cat, 'etoll')
		) {
			return 'e-money';
		}
		if (str_contains($cat, 'pln')) return 'pln';

		return 'lainnya'; // Default jika tidak dikenal
	}

	public function test()
	{
		return Product::where('category','Games')->get();
		return "ok";
	}
}
