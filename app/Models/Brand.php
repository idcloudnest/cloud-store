<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	protected $table = 'brands';

	protected $guarded = ['id'];

	public function scopeCategory($query)
	{
		return $query->select('category')->distinct()->orderBy('category');
	}

	public function scopeListRingkas($query)
	{
		return $query->select('id', 'name', 'slug')->orderBy('name');
	}

	public function scopeActive($query)
	{
		return $query->where('status', 'active');
	}
}
