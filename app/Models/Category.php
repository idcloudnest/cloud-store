<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
	use HasFactory;

	protected $table = 'categories';

	protected $guarded = ['id'];

	/**
	 * Relasi ke Parent Category (Self Join)
	 * Contoh: "Laptop" punya parent "Elektronik"
	 */
	public function parent(): BelongsTo
	{
		// belongsTo(ModelTujuan, Foreign_Key)
		return $this->belongsTo(Category::class, 'parent_id');
	}

	/**
	 * Relasi ke Child Categories (Opsional, untuk masa depan)
	 * Contoh: "Elektronik" punya anak "Laptop", "HP", "TV"
	 */
	public function children(): HasMany
	{
		return $this->hasMany(Category::class, 'parent_id');
	}
	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}
	public function brands(): HasMany
	{
		return $this->hasMany(Brand::class);
	}
}
