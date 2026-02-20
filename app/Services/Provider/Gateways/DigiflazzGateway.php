<?php

namespace App\Services\Provider\Gateways;

use App\Services\Provider\BaseProvider;
use App\Services\Provider\ProviderInterface;

/**
 * Class DigiflazzGateway.
 */
class DigiflazzGateway extends BaseProvider implements ProviderInterface
{
	/**
	 * Generate Signature khusus Digiflazz (MD5)
	 */
	protected function signature(string $refId): string
	{
		$username = $this->provider->api_username;
		$apiKey = $this->provider->api_key;

		return md5($username . $apiKey . $refId);
	}

	public function checkBalance(): array
	{
		return $this->post('/cek-saldo', [
			'username' => $this->provider->api_username,
			'sign'     => $this->signature('depo'),
		]);
	}

	public function productList(string $cmd = 'prepaid'): array
	{
		return $this->post('/price-list', [
			'cmd'      => $cmd,
			'username' => $this->provider->api_username,
			'sign'     => $this->signature('pricelist'),
		]);
	}

	public function transaction(
		string $refId,
		string $skuCode,
		string $destination,
		?string $commands = '',
		// array $options = [],
		// ?bool $testing = null,
		// ?int $maxPrice = null,
		// ?string $cbUrl = null, # Callback url
		// ?bool $allowDot = null, # Value {true} apabila ingin Parameter customer_no berisi titik
		// ?string $commands = null,
	): array
	{
		$payload = [
			'username'       => $this->provider->api_username,
			'buyer_sku_code' => $skuCode,
			'customer_no'    => $destination,
			'ref_id'         => $refId,
			'sign'           => $this->signature($refId),
		];

		// Handle parameter optional via array $options
		// if (isset($options['testing'])) {
		// 	$payload['testing'] = $options['testing'];
		// }

		if ($commands) { # Hanya untuk pascabayar
			$payload['commands'] = $commands;
			// inq-pasca => cek tagihan
			// pay-pasca => bayar tagihan
			// status-pasca => cek status transaksi
		}

		// Jika mode development di DB aktif, otomatis testing = true
		if ($this->provider->mode === 'development') {
			$payload['testing'] = true;
		}

		return $this->post('/transaction', $payload);
	}

	public function checkStatus(string $refId): array
	{
		return $this->post('/transaction', [
			'username' => $this->provider->api_username,
			'ref_id'   => $refId,
			'sign'     => $this->signature($refId),
			// Digiflazz biasanya cek status pakai endpoint transaction yang sama
		]);
	}

	// Fungsi khusus yang tidak ada di interface umum bisa tetap ditambahkan
	public function checkPlnId(string $customerNo): array
	{
		 return $this->post('/inquiry-pln', [
			'username' => $this->provider->api_username,
			'customer_no' => $customerNo,
			'sign' => $this->signature($customerNo),
		]);
	}
}
