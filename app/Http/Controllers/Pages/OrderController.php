<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

# Models
use App\Models\Brand;
use App\Models\Product;

class OrderController extends Controller
{
	public function show($slug)
	{
		$brand = Brand::where('slug', $slug)->with('category')->firstOrFail();

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

		return view('pages.topup.order', compact('brand', 'groupedProducts'));
	}

	public function checkout(Request $request){
		$method = $request->payment_method;

		// if ($method == "QRIS") {
		// 	$
		// }
		return $this->successResponse($request->all(), 'Ok');
	}
}
