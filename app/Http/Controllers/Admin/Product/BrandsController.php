<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandsController extends Controller
{
	public function index()
	{
		return view('admin.product.brands.index');
	}

	public function data(Request $request)
	{
		// Simpan di cache selama 60 menit (3600 detik)
		$data = Cache::remember('brands_list', 3600, function () {
			return Brand::select('id', 'name', 'slug')->get();
		});

		return response()->json($data);
	}
}
