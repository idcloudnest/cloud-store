<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = 'products';

	protected $guarded = ['id'];

	/**
	 * Accessor untuk membuat atribut baru bernama 'price_rupiah'
	 * Laravel 9 ke atas
	 */
	protected function priceRupiah(): Attribute
	{
		return Attribute::make(
			get: fn () => "Rp " . number_format($this->price, 0, ',', '.')
		);
	}

	public function scopeActive($query)
	{
		return $query->where([
			'buyer_product_status' => true,
			'seller_product_status' => true
		]);
	}
}
