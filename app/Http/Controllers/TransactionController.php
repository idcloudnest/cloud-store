<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\Webhook\DigiflazzCallbackService;

class TransactionController extends Controller
{
	protected $digiflazzService;

	public function __construct(DigiflazzCallbackService $digiflazzService)
	{
		$this->digiflazzService = $digiflazzService;
		Config::$serverKey = config('midtrans.server_key');
		Config::$isProduction = config('midtrans.is_production');
		Config::$isSanitized = config('midtrans.is_sanitized');
		Config::$is3ds = config('midtrans.is_3ds');
	}

	public function checkout(Request $request)
	{
		// Harga (Pastikan dikirim sebagai angka tanpa 'Rp' atau titik)
		$amount = $request->nominal;
		$amount = 29000;
		$adminFee = 1000;
		// $orderId = "#1";

		$productPrice = 29000; // Harga Barang
		$adminFee     = 1000;  // Biaya Admin
		$totalAmount  = $productPrice + $adminFee; // 30000 (Total yang harus dibayar)

		$orderId = "TIDCS-" . time(); // Pastikan Order ID Unik

		$data = [
			'invoice' => $orderId,
			'amount' => $productPrice,      // 29000
			'admin_fee' => $adminFee,       // 1000
			'total_amount' => $totalAmount, // 30000 (PENTING)

			// 'user_id' => ##,
			'phone_number' => '6281234567890',
			'customer_no' => '123456',
			'zone_id' => '1234',
			// 'product_id' => 1,
			'product_name_snapshot' => 'Weekly Diamond Pass',
			'sku_snapshot' => 'WDP',
			'payment_method' => '',
			'buy_price' => 28500,
			// 'unique_code' => ##,
			// 'status' => ##,
			'ref_id' => $orderId,
			// 'sn' => ##,
			// 'provider_message' => ##,
		];

		// return $data;
		// 3. Simpan ke Database (Status Pending)
		$transaction = Transaction::create($data);

		// 4. Buat Parameter Transaksi untuk Midtrans
		$params = [
			'transaction_details' => [
				'order_id' => $orderId,
				'gross_amount' => $totalAmount,
			],
			'customer_details' => [
				'first_name' => 'Gamer', // Bisa ambil dari input user
				'phone' => '6281234567890',
			],
			'item_details' => [
				[
					'id' => 'ITEM1',
					'price' => $productPrice,
					'quantity' => 1,
					'name' => 'Weekly Diamond Pass',
				],
				// ITEM 2: BIAYA ADMIN (Agar user tau kenapa jadi 30rb)
				[
					'id' => 'FEE-01',
					'price' => $adminFee,     // 1000
					'quantity' => 1,
					'name' => 'Biaya Layanan',
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
