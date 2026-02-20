<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TelegramBotSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		\App\Models\TelegramBot::create([
			'name'            => 'CLOU NEST REPORT',
			'token'           => '8243536854:AAHTl5Jsi8znqsswz_xw82vM4q6lN_IBesI',
			'default_chat_id' => '-1003511180323',
			'is_active'       => true,
		]);
	}
}
