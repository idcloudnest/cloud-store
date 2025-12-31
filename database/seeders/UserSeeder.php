<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		User::create([
			'name' => 'Dwi Alim N',
			'email' => 'dwialimn27@gmail.com',
			'phone' => '6281335537942',
			'username' => 'dwialim',
			'password' => '$2y$12$a1re3tFsDgE4hO6CvLm2/uPGVsBJICVOvVZZDMNEBzt0AIkvAbVp6',
			'pin' => '$2y$12$J8YD40ZsnzsN3O9Wdfa0s.wsvjTCF45C1Vek2anbKfgh33aY4QguC',
			'balance' => '0',
			'credit_limit' => '999999999',
			'role' => 'admin',
			'is_active' => true,
		]);
		User::create([
			'name' => 'Dev',
			'email' => 'dev@gmail.com',
			'phone' => '6281234567890',
			'username' => 'dev',
			'password' => 'dev',
			'pin' => 'dev',
			'balance' => '0',
			'credit_limit' => '0',
			'role' => 'member',
			'is_active' => true,
		]);
	}
}
