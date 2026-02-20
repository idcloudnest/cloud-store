<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\Transaction; // Sesuaikan model transaksi Anda
use App\Models\Product;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
	// 1. Tampilkan Halaman Checkout (Setelah user klik Beli Sekarang)
	public function checkout(Request $request)
	{
		// return 'tes';
		// Validasi input dari form order
		// $request->validate([
		// 	'product_sku' => 'required',
		// 	'user_id' => 'required', // ID Game / Nomor HP
		// 	'zone_id' => 'nullable', // Zone ID Game (Opsional)
		// ]);

		// return $request->all();
		$product = Product::where('buyer_sku_code', $request->product_sku)->first();

		// Ambil Metode Pembayaran dari Duitku (Opsional: Bisa hardcode atau fetch API)
		$paymentMethods = $this->getDuitkuPaymentMethods($product->selling_price);

		return view('pages.checkout', [
			'product' => $product,
			'target' => $request->user_id . ($request->zone_id ? " ({$request->zone_id})" : ""),
			'paymentMethods' => $paymentMethods
		]);
	}

	// 2. Proses Pembayaran (Request ke Duitku)
	public function processPayment(Request $request)
	{
		$request->validate([
			'payment_method' => 'required', // Contoh: VC (Visa/Master), BC (BCA VA), SP (ShopeePay)
			'product_sku' => 'required',
			'target' => 'required'
		]);

		$product = Product::where('buyer_sku_code', $request->product_sku)->firstOrFail();

		// Generate Nomor Invoice Unik
		$merchantOrderId = 'INV-' . time() . '-' . Str::random(4);

		// --- CONFIG DUITKU ---
		$merchantCode = env('DUITKU_MERCHANT_CODE');
		$apiKey = env('DUITKU_API_KEY');
		$paymentAmount = $product->selling_price;
		$paymentMethod = $request->payment_method; // Kode metode pembayaran
		$productDetails = $product->product_name;
		$customerVaName = 'Customer Guest'; // Nama customer (bisa dari input user)
		$email = 'guest@example.com'; // Email customer
		$phoneNumber = '081234567890'; // No HP customer
		$callbackUrl = route('api.callback.duitku'); // Route callback
		$returnUrl = route('pages.invoice', ['ref' => $merchantOrderId]); // Redirect setelah bayar

		// Signature Duitku (MD5)
		$signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

		// Payload Request
		$params = [
			'merchantCode' => $merchantCode,
			'paymentAmount' => $paymentAmount,
			'paymentMethod' => $paymentMethod,
			'merchantOrderId' => $merchantOrderId,
			'productDetails' => $productDetails,
			'additionalParam' => '',
			'merchantUserInfo' => '',
			'customerVaName' => $customerVaName,
			'email' => $email,
			'phoneNumber' => $phoneNumber,
			'callbackUrl' => $callbackUrl,
			'returnUrl' => $returnUrl,
			'signature' => $signature,
			'expiryPeriod' => 60 // Expire dalam 60 menit
		];

		try {
			// HIT API DUITKU (Sandbox / Production sesuaikan URL)
			$url = env('DUITKU_ENV') === 'production'
				? 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'
				: 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry';

			$response = Http::post($url, $params);
			$result = $response->json();

			if (isset($result['statusCode']) && $result['statusCode'] == '00') {
				// SUKSES DAPAT VA / QRIS

				// Simpan Transaksi ke Database Lokal (Status: UNPAID)
				Transaction::create([
					'ref_id' => $merchantOrderId,
					'product_sku' => $product->buyer_sku_code,
					'product_name' => $product->product_name,
					'target' => $request->target,
					'amount' => $paymentAmount,
					'status' => 'UNPAID',
					'payment_method' => $paymentMethod,
					'payment_ref' => $result['reference'], // Ref Duitku
					'payment_url' => $result['paymentUrl'] ?? null,
					'va_number' => $result['vaNumber'] ?? null,
					'qr_string' => $result['qrString'] ?? null,
				]);

				// Redirect ke Halaman Invoice
				return redirect()->route('pages.invoice', ['ref' => $merchantOrderId]);
			} else {
				return back()->with('error', 'Gagal memproses pembayaran: ' . ($result['statusMessage'] ?? 'Unknown Error'));
			}

		} catch (\Exception $e) {
			return back()->with('error', 'Koneksi ke Payment Gateway Gagal.');
		}
	}

	// Helper: Get Payment Methods from Duitku (Optional)
	private function getDuitkuPaymentMethods($amount)
	{
		// ... (Logic untuk get payment method list dari API Duitku jika mau dinamis)
		// Untuk simpelnya, kita return array manual dulu
		return [
			['code' => 'BC', 'name' => 'BCA Virtual Account', 'group' => 'VA', 'icon' => 'bca.png'],
			['code' => 'M2', 'name' => 'Mandiri Virtual Account', 'group' => 'VA', 'icon' => 'mandiri.png'],
			['code' => 'SP', 'name' => 'QRIS (ShopeePay/Gopay/Dana)', 'group' => 'QRIS', 'icon' => 'qris.png'],
			// Tambahkan metode lain sesuai Duitku
		];
	}

	// 3. Halaman Invoice (Menampilkan VA / QRIS)
	public function invoice($ref)
	{
		$trx = Transaction::where('ref_id', $ref)->firstOrFail();
		return view('pages.invoice', compact('trx'));
	}
}
