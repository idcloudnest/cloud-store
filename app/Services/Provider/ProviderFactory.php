<?php

namespace App\Services\Provider;

use App\Models\Provider;
use App\Services\Provider\Gateways\DigiflazzGateway;
use App\Services\Provider\Gateways\VipaymentGateway;
use Exception;

/**
 * Class ProviderFactory.
 */
class ProviderFactory
{
	/**
	 * MencheckStatusgembalikan Service Instance berdasarkan Provider Code atau ID
	 */
	public static function make(string|int $identifier): ProviderInterface
	{
		// 1. Cari data provider di DB
		if (is_numeric($identifier)) {
			$provider = Provider::find($identifier);
		} else {
			$provider = Provider::where('code', $identifier)->first();
		}

		if (!$provider) {
			throw new Exception("Provider dengan ID/Code '{$identifier}' tidak ditemukan.");
		}

		if (!$provider->is_active) {
			throw new Exception("Provider '{$provider->name}' sedang tidak aktif.");
		}

		// 2. Switch case berdasarkan 'code' (slug) provider
		// Ini menghubungkan data DB dengan Class PHP yang sesuai
		switch ($provider->code) {
			case 'digiflazz':
				return new DigiflazzGateway($provider);
			case 'vipayment':
			    return new VipaymentGateway($provider);
			default:
				throw new Exception("Gateway untuk provider '{$provider->code}' belum diimplementasikan.");
		}
	}
}
