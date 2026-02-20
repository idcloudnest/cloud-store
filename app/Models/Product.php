<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
			get: fn () => formatRupiah($this->price)
		);
	}
	protected function priceNf(): Attribute
	{
		return Attribute::make(
			get: fn () => number_format($this->price, 0, ',', '.')
		);
	}
	protected function sellingPriceRupiah(): Attribute
	{
		return Attribute::make(
			get: fn () => formatRupiah($this->selling_price)
		);
	}
	protected function sellingPriceNf(): Attribute
	{
		return Attribute::make(
			get: fn () => number_format($this->selling_price, 0, ',', '.')
		);
	}
	protected function minValueNf(): Attribute
	{
		return Attribute::make(
			get: fn () => number_format($this->min_value, 0, ',', '.')
		);
	}
	protected function maxValueNf(): Attribute
	{
		return Attribute::make(
			get: fn () => number_format($this->max_value, 0, ',', '.')
		);
	}
	protected function type(): Attribute
	{
		return Attribute::make(
			// Gunakan $value (nilai asli dari database)
			set: fn ($value) => strtolower($value),
		);
	}

	/**
	 * Accessor untuk mengambil Icon berdasarkan category.
	 * Cara panggil di blade: $product->category_icon
	 */
	protected function categoryIcon(): Attribute
	{
		$icons = self::getIcons();
		return Attribute::make(
			get: fn () => $icons[$this->category] ?? 'fa-box',
		);
	}
	// public function getCategoryIconAttribute()
	// {
	// 	$icons = self::getIcons();
	// 	// Cek apakah category ada di array, jika tidak return default (misal: fa-box)
	// 	return $icons[$this->category] ?? 'fa-box';
	// }

	/**
	 * Accessor untuk mengambil Color berdasarkan category.
	 * Cara panggil di blade: $product->category_color
	 */
	protected function categoryColor(): Attribute
	{
		$icons = self::getColors();
		return Attribute::make(
			get: fn () => $icons[$this->category] ?? 'secondary',
		);
	}

	public function scopeActive($query)
	{
		return $query->where([
			'buyer_product_status' => true,
			'seller_product_status' => true
		]);
	}
	public function scopeCategories($query)
	{
		return $query->select('category')->distinct();
	}

	public function scopeIgnoreCheck($query)
	{
		return $query->where('buyer_sku_code', 'NOT LIKE', '%_CEK_%');
	}

	// RELATION
	public function transactions(): HasMany
	{
		return $this->hasMany(Transaction::class);
	}
	public function brand(): BelongsTo
	{
		return $this->belongsTo(Brand::class);
	}
	public function provider(): BelongsTo
	{
		return $this->belongsTo(Provider::class);
	}
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}


	public static function getIcons()
	{
		return [
			'e-money' => 'fa-wallet',
			'pulsa' => 'fa-mobile-alt',
			'games' => 'fa-gamepad',
			'pln' => 'fa-bolt',
			'data' => 'fa-wifi',
			'masa aktif' => 'fa-hourglass-half',
			'streaming' => 'fa-play-circle',
		];
	}

	public static function getColors()
	{
		return [
			'e-money' => 'success',
			'pulsa' => 'primary',
			'games' => 'danger',
			'pln' => 'warning',
			'data' => 'info',
			'masa aktif' => 'dark',
			'streaming' => 'secondary',
		];
	}
}
