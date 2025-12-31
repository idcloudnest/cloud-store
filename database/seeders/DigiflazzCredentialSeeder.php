<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DigiflazzCredential;

class DigiflazzCredentialSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*/
	public function run(): void
	{
		DigiflazzCredential::updateOrCreate(
			['mode' => 'development'],
			[
				'username' => 'fepoheWjJwOW',
				'api_key'  => 'dev-300541c0-e223-11f0-a60f-b7519e7d93e5',
				'is_active' => true,
			]
		);

		DigiflazzCredential::updateOrCreate(
			['mode' => 'production'],
			[
				'username' => 'fepoheWjJwOW',
				'api_key'  => '61b32ce6-0194-5854-b934-a46709ab3096',
				'is_active' => true,
			]
		);
	}
}
