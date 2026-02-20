<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
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
		$request->validate([
			'user_id' => 'required',
			'zone_id' => 'required',
			'nominal' => 'required', // Kirim harga murni (integer) dari frontend
			'item_name' => 'required',
		]);

		// 2. Buat Order ID Unik
		$orderId = 'TRX-' . time() . '-' . rand(100, 999);

		// Harga (Pastikan dikirim sebagai angka tanpa 'Rp' atau titik)
		$amount = $request->nominal;

		// 3. Simpan ke Database (Status Pending)
		$transaction = Transaction::create([
			'order_id' => $orderId,
			'user_id_game' => $request->user_id,
			'zone_id_game' => $request->zone_id,
			'item_name' => $request->item_name,
			'amount' => $amount,
			'status' => 'pending',
		]);

		// 4. Buat Parameter Transaksi untuk Midtrans
		$params = [
			'transaction_details' => [
				'order_id' => $orderId,
				'gross_amount' => $amount,
			],
			'customer_details' => [
				'first_name' => 'Gamer', // Bisa ambil dari input user
				'phone' => $request->whatsapp ?? '08123456789',
			],
			'item_details' => [
				[
					'id' => 'ITEM1',
					'price' => $amount,
					'quantity' => 1,
					'name' => $request->item_name,
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
}
