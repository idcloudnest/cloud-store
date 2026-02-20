<?php

namespace App\Sawit\Models;

use App\Sawit\Model;

class Log extends Model
{
	protected static string $table = 'logs';

	protected static array $fillable = [
		'type',
		'ref_id',
		'message',
		'context',
		'level',
		'created_at',
	];
}
