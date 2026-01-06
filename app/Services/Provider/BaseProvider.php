<?php

namespace App\Services\Provider;

use App\Models\Provider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseProvider.
 */
class BaseProvider
{
	protected Provider $provider;

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * Helper untuk kirim request POST
	 * * @param string $endpoint
	 * @param array $payload
	 * @param bool $isJson (True = application/json, False = x-www-form-urlencoded)
	 */
	protected function post(string $endpoint, array $payload, bool $isJson = true)
	{
		// Menggunakan base_url dari database yang sudah kita buat sebelumnya
		$url = rtrim($this->provider->base_url, '/') . $endpoint;

		// Log request untuk debugging
		// Log::info("Provider Request to {$this->provider->name} ({$endpoint})", $payload);

		// Inisialisasi HTTP Client
		$http = Http::withUserAgent('CloudNest-Store/1.0')
			->timeout(30); // Set timeout agar tidak loading selamanya

		// Opsi: Matikan verifikasi SSL jika di library asli pakai CURLOPT_SSL_VERIFYPEER = false
		// Hapus baris ini jika di production server providernya sudah SSL Valid.
		// $http->withoutVerifying();

		if ($isJson) {
			// Untuk Provider Modern (Digiflazz, dll)
			$response = $http->asJson()->post($url, $payload);
		} else {
			// Untuk Provider Lama/VIP (x-www-form-urlencoded)
			// Ini setara dengan http_build_query() di curl
			$response = $http->asForm()->post($url, $payload);
		}

		// Cek jika error connection (bukan error response API, tapi error jaringan)
		if ($response->failed() && $response->serverError()) {
			Log::error("Base Provider Error {$this->provider->name}: " . $response->body());
		}

		return $response->json();
	}
}
