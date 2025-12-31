<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
	protected $table = 'transactions';

	protected $guarded = ['id'];

	// Event otomatis saat data akan dibuat
	protected static function booted()
	{
		static::creating(function ($transaction) {
			if (empty($transaction->invoice)) {
				$transaction->invoice = static::generateUniqueInvoice();
			}
		});
	}

	public static function generateUniqueInvoice()
	{
		$prefix = 'IDCS';
		$date = now()->format('ymd');

		do {
			// Agar tidak membingungkan saat dibaca manusia
			$pool = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

			// Ambil 6 karakter acak dari pool tersebut
			$random = substr(str_shuffle(str_repeat($pool, 6)), 0, 6);

			// Generate IDCS-240101-ABCDEF
			$invoice = "{$prefix}-{$date}-{$random}";
		} while (self::where('invoice', $invoice)->exists());

		return $invoice;
	}
}
