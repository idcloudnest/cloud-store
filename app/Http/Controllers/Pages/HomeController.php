<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
	public function home()
	{
		// $categories = Category::whereNotIn('id', [1,2])->get(['id', 'name']);
		// $brands = Brand::getCategories()->with('category:id,name,sort_order')->get()->sortBy([
		// 	['category.sort_order', 'asc'],
		// 	['name', 'asc']
		// ])->values();

		// return view('pages.home', compact('brands', 'categories'));

		// Ambil Kategori Utama (kecuali id 1,2 jika itu parent/root)
		$categories = Category::whereNotIn('id', [1, 2])->get(['id', 'name']);

		// Ambil Brand dengan sorting
		$brands = Brand::with('category:id,name,sort_order')
			->get()
			->sortBy([
				['category.sort_order', 'asc'],
				['name', 'asc']
			])->values();

		// [CONTENT IDEAS] Data Dummy untuk Banner Slider
		$banners = [
			['image' => 'https://via.placeholder.com/1200x400/4f46e5/ffffff?text=Promo+Spesial+Awal+Tahun', 'alt' => 'Promo 1'],
			['image' => 'https://via.placeholder.com/1200x400/0ea5e9/ffffff?text=Diskon+Mobile+Legends+50%', 'alt' => 'Promo 2'],
			['image' => 'https://via.placeholder.com/1200x400/f59e0b/ffffff?text=Pulsa+Murah+Tanpa+Admin', 'alt' => 'Promo 3'],
		];

		// [CONTENT IDEAS] Data Dummy Transaksi Terakhir (Running Text)
		$lastTransactions = [
			'0812****9988 sukses membeli MLBB 86 Diamonds',
			'0857****1122 sukses membeli Pulsa Telkomsel 50k',
			'0813****7766 sukses membeli Token PLN 100k',
		];
		return view('pages.home', compact('brands', 'categories', 'banners', 'lastTransactions'));
	}

	public function terms()
	{
		return view('pages.terms-condition');
	}

	public function privacyPolicy()
	{
		return view('pages.privacy-policy');
	}
}
