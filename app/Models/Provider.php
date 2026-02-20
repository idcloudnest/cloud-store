<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
	protected $table = 'providers';

	protected $guarded = ['id'];


	/**
	 * Accessor: Mengubah name menjadi kapital saat diakses
	 */
	protected function name(): Attribute
	{
		return Attribute::make(
			// Gunakan $value (nilai asli dari database)
			get: fn ($value) => strtoupper($value),
			set: fn ($value) => strtolower($value),
		);
	}

	protected function balanceRupiah(): Attribute
	{
		return Attribute::make(
			get: fn () => formatRupiah($this->balance),
		);
	}

	protected function lastUpdate(): Attribute
	{
		return Attribute::make(
			get: fn () => $this->updated_at->diffForHumans(),
		);
	}

	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}
}
