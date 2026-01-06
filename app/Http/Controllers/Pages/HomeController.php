<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Product;

class HomeController extends Controller
{
	public function home()
	{
		$categories = Product::categories()->get();
		$brands = Brand::getCategories()->get();
		return view('pages.home', compact('brands', 'categories'));
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
