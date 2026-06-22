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
			'name' => 'Games',
			'slug' => Str::slug('games'),
			'sort_order' => 0,
		]);
		Category::create([
			'name' => 'Operator Seluler',
			'slug' => Str::slug('Operator Seluler'),
			'sort_order' => 0,
		]);
		Category::create([
			'name' => 'Token PLN',
			'slug' => Str::slug('Token PLN'),
			'sort_order' => 0,
		]);
		Category::create([
			'name' => 'E-Money',
			'slug' => Str::slug('E-Money'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Diamonds',
			'slug' => Str::slug('Diamonds'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Membership',
			'slug' => Str::slug('Membership'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 1,
			'name' => 'Weekly Diamond',
			'slug' => Str::slug('Weekly Diamond'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 2,
			'name' => 'Pulsa',
			'slug' => Str::slug('Pulsa'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 2,
			'name' => 'Data',
			'slug' => Str::slug('Data'),
			'sort_order' => 0,
		]);
		Category::create([
			'parent_id' => 2,
			'name' => 'Masa Aktif',
			'slug' => Str::slug('Masa Aktif'),
			'sort_order' => 0,
		]);
	}
}
