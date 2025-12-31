<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
	public function index()
	{
		return view('admin.product.index');
	}

	public function getProductsByCategory(Request $request)
	{
		$request->validate(['category' => 'required']);

		$products = Product::where('category', $request->category)
			->active()
			->orderBy('price', 'asc')
			->orderBy('product_name', 'asc')
			->get(['buyer_sku_code', 'product_name', 'price']);

		return response()->json($products);
	}
}
