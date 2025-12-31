<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;

class TransactionController extends Controller
{
	public function invoices(Request $request)
	{
		$transaction = null;
		$search = $request->input('search');

		// $transaction = (object) [
		// 	'invoice'      => 'INV-' . date('Ymd') . '-001',
		// 	'status'       => 'success', // Coba ganti: 'pending' atau 'failed'
		// 	'product_name' => 'Token PLN 50.000',
		// 	'product_code' => 'PLN50',
		// 	'amount'       => 51500,
		// 	'target'       => '12345678901', // Nomor Meter/HP
		// 	'sn'           => '1524-5678-9012-3456/SANTOSO/R1/900VA', // Contoh SN Token
		// 	'created_at'   => Carbon::now(),
		// ];
		if ($search) {
			$transaction = Transaction::where('invoice', $search)
							->orWhere('phone_number', $search)
							->latest()
							->first(); // Ambil yang terbaru jika cari pakai No HP

			if (!$transaction) {
				return back()->with('error', 'Transaksi tidak ditemukan. Periksa kembali ID Invoice atau Nomor HP Anda.');
			}
		}

		return view('pages.track', compact('transaction', 'search'));
	}
}
