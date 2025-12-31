<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
	public function __construct()
	{
		Config::$serverKey = config('midtrans.server_key');
		Config::$isProduction = config('midtrans.is_production');
		Config::$isSanitized = config('midtrans.is_sanitized');
		Config::$is3ds = config('midtrans.is_3ds');
	}

	public function checkout(Request $request)
	{
		// 1. Validasi Data
		// $request->validate([
		// 	'user_id' => 'required',
		// 	'zone_id' => 'required',
		// 	'nominal' => 'required', // Kirim harga murni (integer) dari frontend
		// 	'item_name' => 'required',
		// ]);

		// 2. Buat Order ID Unik
		// $orderId = 'TRX-' . time() . '-' . rand(100, 999);

		// Harga (Pastikan dikirim sebagai angka tanpa 'Rp' atau titik)
		$amount = $request->nominal;
		$orderId = "#1";

		// 3. Simpan ke Database (Status Pending)
		$transaction = Transaction::create([
			// 'order_id' => $orderId,
			// 'user_id_game' => $request->user_id,
			// 'zone_id_game' => $request->zone_id,
			// 'item_name' => $request->item_name,
			// 'amount' => $amount,
			// 'status' => 'pending',

			// 'phone_number' => '6281234567890',
			// 'customer_no' => '123456',
			// 'zone_id' => '1234',
			// 'product_id' => 1,
			// 'product_name_snapshot' => 'Weekly Diamond Pass',
			// sku_snapshot
			// 'amount' => 28500,
			// 'status' => 'pending',


			'invoice' => $orderId,
			// 'user_id' => ##,
			'phone_number' => '6281234567890',
			'customer_no' => '123456',
			'zone_id' => '1234',
			'product_id' => 1,
			'product_name_snapshot' => 'Weekly Diamond Pass',
			'sku_snapshot' => 'WDP',
			'payment_method' => ''
			'buy_price' => 28500,
			'amount' => 29000,
			'admin_fee' => 1000
			// 'unique_code' => ##,
			'total_amount' => 30000
			// 'status' => ##,
			'ref_id' => '#1',
			// 'sn' => ##,
			// 'provider_message' => ##,
		]);

		// 4. Buat Parameter Transaksi untuk Midtrans
		$params = [
			'transaction_details' => [
				'order_id' => $orderId,
				'gross_amount' => 29000,
			],
			'customer_details' => [
				'first_name' => 'Gamer', // Bisa ambil dari input user
				'phone' => '6281234567890',
			],
			'item_details' => [
				[
					'id' => 'ITEM1',
					'price' => 29000,
					'quantity' => 1,
					'name' => 'Weekly Diamond Pass',
				]
			]
		];

		// 5. Dapatkan Snap Token
		try {
			$snapToken = Snap::getSnapToken($params);

			// Update token di database
			$transaction->update(['snap_token' => $snapToken]);

			// Kembalikan token ke frontend
			return response()->json(['snap_token' => $snapToken]);

		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}

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
