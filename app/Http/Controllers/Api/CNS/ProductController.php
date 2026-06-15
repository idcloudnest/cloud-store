<?php

namespace App\Http\Controllers\Api\CNS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Models
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;

class ProductController extends Controller
{
	use \App\Traits\ApiResponser;

	public function mainCategory(Request $request)
	{
		$categories = Category::whereNull('parent_id')->get(['id', 'name']);
		// $categories = Category::get(['id', 'name']);
		// $categories = Category::get();

		return $this->successResponse(
			$categories,
			message: 'Ok'
		);
	}

	public function subCategory(Request $request)
	{
		$categories = Category::whereHas('categories', fn($q) => $q->where())->get(['id', 'name']);
		$categories = CategoryProduct::get();

		return $this->successResponse(
			$categories,
			message: 'Ok'
		);
	}

	public function productList(Request $request)
	{
		$products = Product::active()
			->where('brand_id', $request->brand_id)
			->get(['id', 'brand_id', 'product_name', 'selling_price', 'buyer_sku_code', 'start_cut_off', 'end_cut_off']);

		// $groupedProducts = $products
		// 	->groupBy(function ($product) {
		// 		return $this->getMobileLegendGroup($product->buyer_sku_code);
		// 	})
		// 	->map(function ($items) {
		// 		return $items
		// 			->sortBy(function ($product) {
		// 				return $this->getSkuSortNumber($product->buyer_sku_code);
		// 			})
		// 			->values();
		// 	});

		// // $result = [
		// // 	'diamond' => $groupedProducts->get('diamond', collect())->values(),
		// // 	'membership' => $groupedProducts->get('membership', collect())->values(),
		// // 	'diamond_indonesia' => $groupedProducts->get('diamond_indonesia', collect())->values(),
		// // ];
		// $result = $groupedProducts;

		$groupedProducts = $products
			->groupBy(function ($product) {
				return $this->resolveProductGroupKey($product);
			})
			->map(function ($items) {
				return $items
					->sortBy(function ($product) {
						return $this->resolveProductSortNumber($product);
					})
					->values();
			});

		$groupOrder = [
			'pulsa',
			'data',
			'pln',
			'masa_aktif',
			'diamond',
			'membership',
			'diamond_indonesia',
			'game',
			'voucher',
			'emoney',
			'lainnya',
		];
		$result = collect($groupOrder)
			->filter(fn ($key) => $groupedProducts->has($key))
			->map(function ($key) use ($groupedProducts) {
				$products = $groupedProducts->get($key)->values();

				if ($key === 'data') {
					return [
						'name' => $this->resolveProductGroupName($key),
						'groups' => $this->groupDataProductsBySku($products),
					];
				}

				return [
					'name' => $this->resolveProductGroupName($key),
					'products' => $products,
				];
			})
			->values();
		// $result = collect($groupOrder)
		// 	->filter(fn ($key) => $groupedProducts->has($key))
		// 	->map(function ($key) use ($groupedProducts) {
		// 		return [
		// 			'name' => $this->resolveProductGroupName($key),
		// 			'products' => $groupedProducts->get($key)->values(),
		// 		];
		// 	})->values();
			// ->mapWithKeys(function ($key) use ($groupedProducts) {
			// 	return [
			// 		$key => [
			// 			'name' => $this->resolveProductGroupName($key),
			// 			'products' => $groupedProducts->get($key)->values(),
			// 		],
			// 	];
			// });

		return $this->successResponse(
			$result,
			message: 'Ok'
		);
	}

	public function brandList(Request $request)
	{
		$category = $request->category ?? 'active';

		$brands = Brand::when($category == 'active', fn($q) => $q->where('status', 1))
			->orderBy('name')->get(['id','name']);

		return $this->successResponse(
			$brands,
			message: 'Ok'
		);
	}

	// ====================
	private function groupDataProductsBySku($products)
	{
		return $products
			->groupBy(function ($product) {
				return $this->resolveDataSkuGroupKey($product->buyer_sku_code);
			})
			->map(function ($items, $key) {
				return [
					'key' => $key,
					'name' => $this->resolveDataSkuGroupName($key),
					'products' => $items
						->sortBy(function ($product) {
							return $this->resolveDataSortNumber($product);
						})
						->values(),
				];
			})
			->sortBy(function ($group) {
				return $this->resolveDataGroupOrder($group['key']);
			})
			->values();
	}

	private function resolveDataSkuGroupKey(?string $sku): string
	{
		if (!$sku) {
			return 'lainnya';
		}

		$sku = strtoupper($sku);

		if (!str_starts_with($sku, 'DATA,')) {
			return 'lainnya';
		}

		// DATA,TRI_AON_1 => TRI_AON
		// DATA,TRI_HPY_5G_1 => TRI_HPY_5G
		$payload = str_replace('DATA,', '', $sku);
		$groupKey = preg_replace('/_\d+$/', '', $payload);

		return strtolower($groupKey ?: 'lainnya');
	}

	private function resolveDataSkuGroupName(string $key): string
	{
		return match ($key) {
			'tri_aon' => 'AlwaysOn',
			'tri_happy' => 'Happy',
			'tri_hpy_5g' => 'Happy 5G',
			'tri_umum' => 'Umum',
			default => Str::of($key)
				->replace('_', ' ')
				->title()
				->toString(),
		};
	}

	private function resolveDataGroupOrder(string $key): int
{
    return match ($key) {
        'tri_aon' => 1,
        'tri_happy' => 2,
        'tri_hpy_5g' => 3,
        'tri_umum' => 4,
        default => 999,
    };
}

private function resolveDataSortNumber($product): float
{
    $name = strtoupper($product->product_name ?? '');

    // 1.5 GB => 1536
    // 2 GB => 2048
    // 500 MB => 500
    if (preg_match('/(\d+(?:[.,]\d+)?)\s*(GB|MB)\b/', $name, $matches)) {
        $value = (float) str_replace(',', '.', $matches[1]);
        $unit = $matches[2];

        return $unit === 'GB'
            ? $value * 1024
            : $value;
    }

    // fallback: ambil angka suffix SKU
    $sku = strtoupper($product->buyer_sku_code ?? '');

    if (preg_match('/_(\d+)$/', $sku, $matches)) {
        return (float) $matches[1];
    }

    return PHP_INT_MAX;
}
	// ====================

	private function resolveProductGroupKey($product): string
	{
		$sku = strtoupper($product->buyer_sku_code ?? '');
		$label = Str::of($product->label ?? '')
			->lower()
			->replace('-', '_')
			->toString();

		// Special case Mobile Legends
		if (Str::startsWith($sku, 'GAME,ML_MSHIP')) {
			return 'membership';
		}

		if (Str::startsWith($sku, 'GAME,MLID')) {
			return 'diamond_indonesia';
		}

		if (Str::startsWith($sku, 'GAME,ML_')) {
			return 'diamond';
		}

		// Generic by label
		if ($label === 'pulsa') {
			return 'pulsa';
		}

		if (in_array($label, ['data', 'paket_data', 'internet'])) {
			return 'data';
		}

		if ($label === 'pln') {
			return 'pln';
		}

		if ($label === 'masa_aktif') {
			return 'masa_aktif';
		}

		if ($label === 'game') {
			return 'game';
		}

		if (in_array($label, ['voucher', 'voucher_game'])) {
			return 'voucher';
		}

		if (in_array($label, ['e_money', 'emoney'])) {
			return 'emoney';
		}

		return $label ?: 'lainnya';
	}

	private function resolveProductGroupName(string $key): string
	{
		return match ($key) {
			'pulsa' => 'Pulsa',
			'data' => 'Paket Data',
			'pln' => 'Token PLN',
			'masa_aktif' => 'Masa Aktif',
			'diamond' => 'Diamond',
			'membership' => 'Membership',
			'diamond_indonesia' => 'Diamond Indonesia',
			'game' => 'Game',
			'voucher' => 'Voucher',
			'emoney' => 'E-Money',
			default => 'Lainnya',
		};
	}

	private function resolveProductSortNumber($product): float
	{
		$sku = strtoupper($product->buyer_sku_code ?? '');
		$name = strtoupper($product->product_name ?? '');

		/*
		* Contoh:
		* PULSA,IM3_10K => 10000
		* PULSA,IM3_100K => 100000
		* PLN,5K => 5000
		* PLN,1000K => 1000000
		*/
		if (preg_match('/(?:_|,)(\d+(?:\.\d+)?)(K|RB|RIBU)\b/', $sku, $matches)) {
			return (float) $matches[1] * 1000;
		}

		/*
		* Contoh:
		* MASA-AKTIF,IM3_14D => 14
		* MASA-AKTIF,AXIS_30D => 30
		*/
		if (preg_match('/_(\d+(?:\.\d+)?)D\b/', $sku, $matches)) {
			return (float) $matches[1];
		}

		/*
		* Contoh:
		* GAME,ML_5 => 5
		* GAME,ML_100 => 100
		* GAME,ML_MSHIP_1 => 1
		*/
		if (preg_match('/_(\d+(?:\.\d+)?)$/', $sku, $matches)) {
			return (float) $matches[1];
		}

		/*
		* Untuk paket data:
		* 500 MB => 500
		* 1 GB => 1024
		* 10 GB => 10240
		*/
		if (preg_match('/(\d+(?:\.\d+)?)\s*(GB|MB)\b/', $name, $matches)) {
			$value = (float) $matches[1];
			$unit = $matches[2];

			return $unit === 'GB'
				? $value * 1024
				: $value;
		}

		/*
		* Fallback ambil angka pertama dari nama produk.
		* Contoh:
		* Indosat 10.000 => 10000
		* PLN 1.000.000 => 1000000
		*/
		if (preg_match('/(\d+(?:[.,]\d{3})*)/', $name, $matches)) {
			return (float) str_replace(['.', ','], '', $matches[1]);
		}

		return PHP_INT_MAX;
	}

	// private function getMobileLegendGroup(?string $sku): string
	// {
	// 	if (!$sku) {
	// 		return 'lainnya';
	// 	}

	// 	if (Str::startsWith($sku, 'GAME,ML_MSHIP')) {
	// 		return 'membership';
	// 	}

	// 	if (Str::startsWith($sku, 'GAME,MLID')) {
	// 		return 'diamond_indonesia';
	// 	}

	// 	if (Str::startsWith($sku, 'GAME,ML_')) {
	// 		return 'diamond';
	// 	}

	// 	return 'lainnya';
	// }

	// private function getSkuSortNumber(?string $sku): int
	// {
	// 	if (!$sku) {
	// 		return PHP_INT_MAX;
	// 	}

	// 	preg_match('/_(\d+)$/', $sku, $matches);

	// 	return isset($matches[1])
	// 		? (int) $matches[1]
	// 		: PHP_INT_MAX;
	// }
}
