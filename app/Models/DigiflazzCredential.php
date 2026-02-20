<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigiflazzCredential extends Model
{
	protected $table = "digiflazz_credentials";

	protected $fillable = [
		'username',
		'api_key',
		'mode',
		'base_url',
		'is_active',
	];

	protected $hidden = [
		'api_key',
	];

	/* Scope berdasarkan mode */
	public function scopeActiveMode($query, string $mode)
	{
		return $query->where('mode', $mode)->where('is_active', true);
	}
}
