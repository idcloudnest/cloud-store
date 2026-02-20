<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;

class ProductController extends Controller
{
	// public function topup(Request $request, $slug)
	// {
	// 	// $enabledPayments = [];
	// 	// if ($request->payment_method == 'DANA') {
	// 	// 	$enabledPayments = ['gopay', 'shopeepay']; // Sesuai kode Midtrans
	// 	// }

	// 	// $params = [
	// 	// 	'enabled_payments' => $enabledPayments,
	// 	// 	// ... params lainnya
	// 	// ];

	// 	return view('pages.topup.detail', ['slug' => $slug]);
	// }

	public function topup($slug)
	{
		// 1. Cari Brand berdasarkan Slug
		$brand = Brand::where('slug', $slug)->firstOrFail();

		// 2. Ambil Produk milik Brand ini
		// Kita group berdasarkan 'type' (misal: 'pulsa', 'data', 'masa_aktif')
		// Atau berdasarkan 'category_id' jika struktur DB Anda memisahkan kategori produk
		// Di sini saya asumsikan kolom 'type' di table 'products' digunakan untuk pemisah (umum/data/dll)
		$products = Product::where('brand_id', $brand->id)
			->where('buyer_product_status', true) // Hanya yang aktif
			->orderBy('price', 'asc')
			->get();

		// Grouping untuk Tab
		// Mapping type database ke Label yang user friendly
		$groupedProducts = $products->groupBy(function ($item) {
			$exp = explode(',', $item->buyer_sku_code);
			$type = strtolower($exp[0] ?? '');
			if ($type === 'game')
				return explode('_', $exp[1])[0] ?? '';

			return $type;
		});

		return view('pages.topup.detail', compact('brand', 'groupedProducts'));
	}
}
