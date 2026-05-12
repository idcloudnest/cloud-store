<?php

namespace App\Services\Provider\Gateways;

use App\Services\Provider\BaseProvider;
use App\Services\Provider\ProviderInterface;

/**
 * Class VipaymentGateway.
 */
class VipaymentGateway extends BaseProvider implements ProviderInterface
{
	/**
	 * Generate Signature khusus Vipayment (MD5)
	 */
	protected function signature(): string
	{
		$username = $this->provider->api_username;
		$apiKey = $this->provider->api_key;

		return md5($username . $apiKey);
	}

	public function profile(): array
	{
		return $this->post('/profile', [
			'key'  => $this->provider->api_key,
			'sign' => $this->signature(),
		], false);
	}

	public function servicePrepaid(?string $filterType = null, ?string $filterValue = null): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'services',
			'filter_type' => $filterType,
			'filter_value' => $filterValue
		];

		return $this->post('/prepaid', $payload, false);
	}
	public function orderPrepaid(string $serviceCode, string $dataNo): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'order',
			'service' => $serviceCode,
			'data_no' => $dataNo
		];

		return $this->post('/prepaid', $payload, false);
	}
	public function statusOrderPrepaid(string $trxId, ?int $limit = null): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'status',
			'trxid' => $trxId,
			'limit' => $limit
		];

		return $this->post('/prepaid', $payload, false);
	}

	public function serviceGame(?string $filterGame = null, ?string $filterStatus = null): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'services',
			// 'filter_game' => $filterGame,
			// 'filter_status' => $filterStatus
		];

		if ($filterGame)
			$payload['filter_game'] = $filterGame;
		if ($filterStatus)
			$payload['filter_status'] = $filterStatus;

		// \Log::debug(json_encode($payload, JSON_PRETTY_PRINT));
		return $this->post('/game-feature', $payload, false);
	}
	public function orderGame(string $service, string $dataNo, ?string $dataZone = null): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'order',
			'service' => $service,
			'data_no' => $dataNo,
			'data_zone' => $dataZone
		];

		return $this->post('/game-feature', $payload, false);
	}
	public function statusOrderGame(string $trxId, ?int $limit = null): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'status',
			'trxid' => $trxId,
			'limit' => $limit
		];

		return $this->post('/game-feature', $payload, false);
	}

	/*
	*List game sewaktu-waktu dapat update
	* Game yang tersedia:
		- Mobile Legends : mobile-legends
		- Hago : hago
		- Zepeto : zepeto
		- Lords Mobile : lords-mobile
		- Marvel Super War : marvel-super-war
		- Ragnarok M : ragnarok-m-eternal-love-big-cat-coin
		- Speed Drifters : speed-drifters
		- Laplace M : laplace-m
		- Valorant : valorant
		- Higgs Domino : higgs-domino
		- Point Blank : point-blank
		- Dragon Raja : dragon-raja
		- League of Legends: Wild Rift : league-of-legends-wild-rift (testing)
		- Free Fire : free-fire
		- Free Fire Max : free-fire-max
		- Tom and Jerry:chase : tom-and-jerry-chase
		- Cocofun : cocofun (testing)
		- 8 Ball Pool : 8-ball-pool (testing)
		- Auto Chess : auto-chess (testing)
		- Bullet Angel : bullet-angel (testing)
		- Arena of Valor : arena-of-valor
		- Call of Duty MOBILE : call-of-duty-mobile
		- Genshin Impact : genshin-impact | server : 'os_asia', 'os_usa', 'os_euro', 'os_cht'
		- IndoPlay : indoplay
		- Domino Gaple Boyaa Qiuqiu : domino-gaple-qiuqiu-boyaa
	*/
	public function checkUsername(string $codeGame, string $userId, string $zoneId): array
	{
		$payload = [
			'key' => $this->provider->api_key,
			'sign' => $this->signature(),
			'type' => 'get-nickname',
			'code' => $codeGame,
			'target' => $userId,
			'additional_target' => $zoneId,
		];

		return $this->post('/game-feature', $payload, false);
	}



	public function checkBalance(): array
	{
		return $this->profile();
	}

	public function productList(): array
	{
	}

	public function transaction(
		string $refId,
		string $skuCode,
		string $destination,
		array $options = [],
	): array
	{
	}

	public function checkStatus(string $refId): array
	{
	}

	// Fungsi khusus yang tidak ada di interface umum bisa tetap ditambahkan
	public function checkPlnId(string $customerNo): array
	{
	}
}
