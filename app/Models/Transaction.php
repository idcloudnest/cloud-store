<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
	protected $table = 'transactions';

	protected $guarded = ['id'];

	// Event otomatis saat data akan dibuat
	protected static function booted()
	{
		static::creating(function ($transaction) {
			if (empty($transaction->invoice)) {
				$invoice = static::generateUniqueInvoice();
				$transaction->invoice = $invoice;
				$transaction->ref_id = $invoice;
			}
		});
	}

	public static function generateUniqueInvoice()
	{
		$prefix = 'CNS';
		$date = now()->format('ymd');

		do {
			// Agar tidak membingungkan saat dibaca manusia
			$pool = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

			// Ambil 6 karakter acak dari pool tersebut
			$random = substr(str_shuffle(str_repeat($pool, 6)), 0, 6);

			// Generate CNS-240101-ABCDEF
			$invoice = "{$prefix}-{$date}-{$random}";
		} while (self::where('invoice', $invoice)->exists());

		return $invoice;
	}

	/**
	 * Accessor untuk membuat atribut baru bernama 'total_rupiah'
	 * Laravel 9 ke atas
	 */
	protected function totalRupiah(): Attribute
	{
		return Attribute::make(
			get: fn () => "Rp " . number_format($this->total_amount, 0, ',', '.')
		);
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
