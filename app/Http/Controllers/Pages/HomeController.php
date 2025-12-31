<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function home()
	{
		return view('pages.home');
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
