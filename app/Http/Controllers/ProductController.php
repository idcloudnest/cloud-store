<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function topup(Request $request, $slug)
	{
		$enabledPayments = [];
		if ($request->payment_method == 'DANA') {
			$enabledPayments = ['gopay', 'shopeepay']; // Sesuai kode Midtrans
		}

		$params = [
			'enabled_payments' => $enabledPayments,
			// ... params lainnya
		];

		return view('pages.topup.detail', ['slug' => $slug]);
	}
}
