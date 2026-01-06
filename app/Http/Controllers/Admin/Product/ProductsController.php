<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{

	use \App\Traits\ApiResponser;

	public function index()
	{
		return view('admin.product.index');
	}

	public function getBrandsByCategory(Request $request)
	{
		$brands = Product::where('category', $request->category)
			->select('brand')
			->distinct()
			->orderBy('brand', 'asc')
			->get();

		return $this->successResponse($brands, 'Ok.');
	}
	public function getProductsByCategory(Request $request)
	{
		// $request->validate(['category' => 'required']);

		$products = Product::active()
			->when($request->brand, fn($q) => $q->where('brand', $request->brand))
			->when(!$request->brand, fn($q) => $q->where('category', $request->category))
			->ignoreCheck()
			->orderBy('price', 'asc')
			->orderBy('product_name', 'asc')
			->get(['id','brand','buyer_sku_code', 'product_name', 'price']);

		return $this->successResponse($products, 'Ok.');
	}
}
