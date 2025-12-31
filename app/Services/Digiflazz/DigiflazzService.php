<?php

namespace App\Services\Digiflazz;

use App\Models\DigiflazzCredential;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
* Class DigiflazzService.
*/
class DigiflazzService
{
	protected string $username;
	protected string $apiKey;
	protected string $baseUrl;

	public function __construct()
	{
		$mode = config('app.env') === 'production' ? 'production' : env('DIGIFLAZZ_MODE', 'development');

		$credential = Cache::remember(
			"digiflazz_credential_{$mode}",
			600,
			fn () => DigiflazzCredential::activeMode($mode)->first()
		);

		if (!$credential)
			throw new Exception("Credential DigiFlazz mode {$mode} tidak ditemukan");

		$this->username = $credential->username;
		$this->apiKey   = $credential->api_key;
		$this->baseUrl  = $credential->base_url;
	}

	protected function signature(?string $refId): string
	{
		return md5($this->username . $this->apiKey . $refId);
	}

	protected function post(string $endpoint, array $payload)
	{
		return Http::post($this->baseUrl . $endpoint, $payload)->json();
	}

	/* ================= DIGIFLAZZ API ================= */
	public function checkBalance(): array
	{
		// $refId = 'saldo_' . time();
		$refId = 'depo';

		return $this->post('/cek-saldo', [
			'username' => $this->username,
			'sign'     => $this->signature($refId),
		]);
	}

	public function productList(): array
	{
		$refId = 'pricelist';

		return $this->post('/price-list', [
			'username' => $this->username,
			'sign'     => $this->signature($refId),
		]);
	}

	public function transaction(
		string $refId, # Unique Id (auto generate)
		string $buyerSkuCode,
		string $customerNo,
		?bool $testing = null,
		?int $maxPrice = null,
		?string $cbUrl = null, # Callback url
		?bool $allowDot = null, # Value {true} apabila ingin Parameter customer_no berisi titik
		?string $commands = null,
	): array
	{
		$payload = [
			'username'       => $this->username,
			'buyer_sku_code' => $buyerSkuCode,
			'customer_no'    => $customerNo,
			'ref_id'         => $refId,
			'sign'           => $this->signature($refId),
		];

		if ($maxPrice !== null && $maxPrice > 0)
			$payload['max_price'] = $maxPrice;

		if ($testing !== null)
			$payload['testing'] = $testing;

		if ($cbUrl !== null)
			$payload['cb_url'] = $cbUrl;

		if ($allowDot !== null)
			$payload['allow_dot'] = $allowDot;

		if ($commands !== null && in_array($commands, ['inq-pasca', 'pay-pasca', 'status-pasca']))
			$payload['commands'] = $commands;

		return $this->post('/transaction', $payload);
	}
}
