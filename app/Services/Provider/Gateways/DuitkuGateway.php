<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuGateway extends BaseProvider
{
	protected $merchantCode;
	protected $apiKey;
	protected $baseUrl;
	protected $callbackUrl;
	protected $returnUrl;

	public function __construct()
	{
		$this->merchantCode = config('duitku.merchant_code');
		$this->apiKey       = config('duitku.api_key');
		$this->callbackUrl  = config('duitku.callback_url');
		$this->returnUrl    = config('duitku.return_url');
		$this->baseUrl      = config('duitku.base_url');
	}

	/**
	 * Request Transaksi (Create Invoice)
	 */
	public function createInvoice($orderId, $amount, $paymentMethod, $productDetails, $customerData = [])
	{
		// 1. Generate Signature
		$signature = md5($this->merchantCode . $orderId . $amount . $this->apiKey);

		// 2. Siapkan Payload
		$params = [
			'merchantCode'    => $this->merchantCode,
			'paymentAmount'   => $amount,
			'paymentMethod'   => $paymentMethod,
			'merchantOrderId' => $orderId,
			'productDetails'  => $productDetails,
			'additionalParam' => '',
			'merchantUserInfo'=> '',
			'customerVaName'  => $customerData['name'] ?? 'Guest Customer',
			'email'           => $customerData['email'] ?? 'guest@example.com',
			'phoneNumber'     => $customerData['phone'] ?? '08123456789',
			'callbackUrl'     => $this->callbackUrl,
			'returnUrl'       => $this->returnUrl . $orderId, // Redirect balik ke detail invoice
			'signature'       => $signature,
			'expiryPeriod'    => 60 // Expire dalam 60 menit
		];

		try {
			// 3. Hit API
			$response = Http::post($this->baseUrl . '/inquiry', $params);
			$result = $response->json();

			// 4. Log untuk debugging (Opsional tapi Recommended)
			Log::info('DUITKU REQUEST', ['params' => $params, 'response' => $result]);

			if (isset($result['statusCode']) && $result['statusCode'] == '00') {
				return [
					'success' => true,
					'data'    => $result
				];
			} else {
				return [
					'success' => false,
					'message' => $result['statusMessage'] ?? 'Unknown Error from Duitku'
				];
			}

		} catch (\Exception $e) {
			Log::error('DUITKU ERROR: ' . $e->getMessage());
			return [
				'success' => false,
				'message' => 'Connection Error: ' . $e->getMessage()
			];
		}
	}

	/**
	 * Get Payment Methods (Opsional: Ambil list metode pembayaran dari API)
	 */
	public function getPaymentMethods($amount)
	{
		// Logic hit API getPaymentMethod Duitku disini jika perlu
		// Signature beda lagi logicnya
		$signature = hash('sha256',$merchantCode . $paymentAmount . $datetime . $apiKey);
	}
}
