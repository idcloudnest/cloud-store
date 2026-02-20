<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

// Models
use App\Models\Category;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Category::create([
			'name' => 'Prabayar',
			'slug' => Str::slug('Prabayar') . '-' . Str::random(5),
			'sort_order' => 0,
		]);
		Category::create([
			'name' => 'Pascabayar',
			'slug' => Str::slug('Pascabayar') . '-' . Str::random(5),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Operator Seluler',
			'slug' => Str::slug('Operator Seluler') . '-' . Str::random(5),
			'sort_order' => 1,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Token Listrik',
			'slug' => Str::slug('Token Listrik') . '-' . Str::random(5),
			'sort_order' => 2,
		]);
		Category::create([
			'parent_id' => 2,
			'name' => 'Tagihan (PPOB)',
			'slug' => Str::slug('Tagihan (PPOB)') . '-' . Str::random(5),
			'sort_order' => 2,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Games',
			'slug' => Str::slug('Games') . '-' . Str::random(5),
			'sort_order' => 3,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'E-Wallet',
			'slug' => Str::slug('E-Wallet') . '-' . Str::random(5),
			'sort_order' => 4,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Voucher',
			'slug' => Str::slug('Voucher') . '-' . Str::random(5),
			'sort_order' => 5,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Streaming',
			'slug' => Str::slug('Streaming') . '-' . Str::random(5),
			'sort_order' => 6,
		]);
	}
}
