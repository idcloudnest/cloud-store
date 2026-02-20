<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
	protected $table = 'brands';

	protected $guarded = ['id'];

	// protected $appends = ['category_list'];

	public function scopeCategory($query)
	{
		return $query->select('category','icon','color')->distinct()->orderBy('category');
	}

	public function scopeListRingkas($query)
	{
		return $query->select('id', 'name', 'slug')->orderBy('name');
	}
	public function scopeGetCategories($query)
	{
		return $query->with(['products' => fn ($q) => $q->select('provider_id', 'brand_id', 'category_id')->distinct()->with('provider:id,name')]);
	}

	public function scopeActive($query)
	{
		return $query->where('status', 'active');
	}

	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}

	// public function getCategoryListAttribute()
	// {
	// 	// Cek apakah relasi products sudah di-load agar tidak error
	// 	if ($this->relationLoaded('products')) {
	// 		return $this->products
	// 			->pluck('category_id')
	// 			->unique()
	// 			->values()
	// 			->all();
	// 	}

	// 	return [];
	// }

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}
}
