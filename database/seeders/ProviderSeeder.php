<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Provider::updateOrCreate(
		// 	[
		// 		'mode' => 'development',
		// 		'code' => 'digiflazz',
		// 	],
		// 	[
		// 		'name' => 'digiflazz',
		// 		'api_username' => 'fepoheWjJwOW',
		// 		'api_key'  => 'dev-300541c0-e223-11f0-a60f-b7519e7d93e5',
		// 		'base_url' => 'https://api.digiflazz.com/v1',
		// 		'is_active' => true,
		// 	]
		// );
		Provider::updateOrCreate(
			[
				'code' => 'digiflazz',
			],
			[
				'name' => 'digiflazz',
				'api_username' => 'fepoheWjJwOW',
				'api_key'  => '61b32ce6-0194-5854-b934-a46709ab3096',
				'secret_key'  => 'x4eJCNez9Ayx5wv1Nfk3aJDMfmij8LUo',
				'base_url' => 'https://api.digiflazz.com/v1',
				'mode' => 'production',
				'is_active' => true,
			]
		);

		Provider::updateOrCreate(
			[
				'code' => 'vipayment',
			],
			[
				'name' => 'vipayment (vip-reseller)',
				'api_username' => 'Pkat7cQB',
				'api_key'  => 'wNyaabLNwzmS0aL01I4SFbanZDKE2NTZFfKsPhcis2IhMlWSx75hH7YeNZcSJ3la',
				'secret_key'  => 'c0446aabb0b3f355ea70118c1a08c2e8',
				'base_url' => 'https://vip-reseller.co.id/api',
				'mode' => 'production',
				'is_active' => true,
			]
		);
	}
}
