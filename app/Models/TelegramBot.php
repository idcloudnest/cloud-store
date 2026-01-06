<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model
{
	use HasFactory;

	protected $table = 'telegram_bots';
	protected $guarded = ['id'];

	public static function getActiveBot()
	{
		return self::where('is_active', true)->first();
	}
}
