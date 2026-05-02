<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Provider\ProviderFactory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Provider;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Services\TelegramService;

class SyncDigiflazzProduct extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'digiflazz:sync-product
		{provider_id : ID provider}
		{type : prepaid|pascabayar}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync product list from Digiflazz provider';

	/**
	 * Execute the console command.
	 */
	public function handle(TelegramService $telegram): int
	{
		$providerId = (int) $this->argument('provider_id');
		$type       = $this->argument('type');

		DB::beginTransaction();

		try {
			$provider = Provider::findOrFail($providerId);
			$service  = ProviderFactory::make($provider->code);

			$this->info("Syncing product from provider: {$provider->name} ({$type})");

			$response = $service->productList($type);

			// if ($type !== 'prepaid') {
			// 	Log::debug(json_encode($this->argument(), JSON_PRETTY_PRINT));
			// 	Log::debug(json_encode($response, JSON_PRETTY_PRINT));
			// }

			// ===== VALIDASI RESPONSE =====
			if (!isset($response['data']) || !is_array($response['data'])) {
				throw new Exception('Invalid response from provider');
			}

			if (isset($response['data']['rc']) && $response['data']['rc'] == 83) {
				throw new Exception($response['data']['message']);
			}


			$processed = 0;
			$productNonActive = [];
			foreach ($response['data'] as $item) {

				if ($item['seller_product_status'] == false) {
					$string = (count($productNonActive) + 1) . ". <b>{$item['product_name']}</b>\n";
					$string .= "    <b>Kode:</b> {$item['buyer_sku_code']}\n";
					$productNonActive[] =  $string;
				}

				// ===== BRAND =====
				$brand = Brand::firstOrCreate(
					['slug' => Str::slug($item['brand'])],
					[
						'name'   => strtoupper($item['brand']),
						'status' => 1
					]
				);

				// ===== STATUS =====
				$isActive = $item['buyer_product_status'] && $item['seller_product_status'];

				// ===== PRICE =====
				if ($type === 'prepaid') {
					$price       = $item['price'];
					$commission  = 0;
					$productType = $item['type'] ?? 'umum';
					$startCutOff = $item['start_cut_off'];
					$endCutOff   = $item['end_cut_off'];
				} else {
					$commission  = $item['commission'];
					$price       = max(0, $item['admin'] - $commission);
					$productType = 'pascabayar';
					$startCutOff = '00:00:00';
					$endCutOff   = '00:00:00';
				}

				// ===== PRODUCT =====
				Product::updateOrCreate(
					['buyer_sku_code' => $item['buyer_sku_code']],
					[
						'provider_id'           => $providerId,
						'brand_id'              => $brand->id,
						'product_name'          => $item['product_name'],
						'price'                 => $price,
						'commission'            => $commission,
						'type'                  => $productType,
						'seller_name'           => $item['seller_name'],
						'start_cut_off'         => $startCutOff,
						'end_cut_off'           => $endCutOff,
						// 'status'                => $isActive,
						'stock'                 => $item['stock'] ?? 0,
						'unlimited_stock'       => $item['unlimited_stock'] ?? true,
						'multi'                 => $item['multi'] ?? false,
						'seller_product_status' => $item['seller_product_status'],
						'buyer_product_status'  => $item['buyer_product_status'],
					]
				);

				$processed++;
			}

			DB::commit();

			$this->info("✅ [" . now()->translatedFormat('d M Y H:i:s') . "] Sync selesai. Total produk: {$processed}");

			// Log::info('DIGIFLAZZ PRODUCT SYNC SUCCESS', [
			// 	'provider' => $provider->name,
			// 	'type'     => $type,
			// 	'total'    => $processed
			// ]);

			if (count($productNonActive)) {
				$telegram->sendInactiveProducts(implode("\n", $productNonActive), "\nℹ️ Keterangan: Maintenance provider");
			}

			return Command::SUCCESS;

		} catch (Exception $e) {
			DB::rollBack();

			$this->error("❌ [" . now()->translatedFormat('d M Y H:i:s') . "] Sync gagal: {$e->getMessage()}");

			Log::error('DIGIFLAZZ PRODUCT SYNC FAILED', [
				'provider_id' => $providerId,
				'type'        => $type,
				'error'       => $e->getMessage()
			]);

			return Command::FAILURE;
		}
	}
}
