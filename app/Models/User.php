<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Penting: Agar history user aman
use Laravel\Sanctum\HasApiTokens; // Penting: Untuk akses API

class User extends Authenticatable
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'email',
		'phone',
		'username',
		'password',
		'pin',
		'pin_active',
		'balance',
		'credit_limit',
		'role',
		'is_active',
		'api_key',
		'jabber_id',
		'allowed_ip',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'pin',           // Jangan expose PIN
		'api_key',       // Jangan expose API Key
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			'pin'       => 'hashed',    // Otomatis hash saat set/update PIN
			'is_active' => 'boolean',   // Ubah 1/0 jadi true/false
			'balance'   => 'decimal:2', // Pastikan format angka uang presisi
			'credit_limit' => 'decimal:2',
		];
	}

	/**
	 * Accessor: Format Saldo ke Rupiah (Virtual Attribute).
	 * Cara panggil: $user->balance_formatted
	 */
	public function getBalanceFormattedAttribute(): string
	{
		return formatRupiah($this->balance);
	}

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
