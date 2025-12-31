<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();

			// --- Identitas Utama ---
			$table->string('name');
			$table->string('email')->unique();
			$table->string('phone', 20)->unique()->nullable(); // Penting untuk notifikasi WA/SMS
			$table->string('username')->unique()->nullable(); // Opsional, login pakai username

			// --- Keamanan ---
			$table->string('password'); // Password Login Dashboard
			$table->string('pin', 255)->nullable(); // PIN Transaksi (Dihash seperti password)
			$table->boolean('pin_active')->default(false);

			// --- Keuangan ---
			// Decimal (15, 2) mendukung angka hingga 9 Triliun dengan 2 desimal (Rp 9.999.999.999.999,00)
			$table->decimal('balance', 19, 2)->default(0);
			$table->decimal('credit_limit', 15, 2)->default(0); // Fitur hutang (opsional)

			// --- Level & Akses ---
			$table->enum('role', ['admin', 'reseller', 'member', 'h2h'])->default('member');
			$table->boolean('is_active')->default(true); // Untuk suspend user nakal

			// --- Jalur Transaksi (H2H & Jabber) ---
			$table->string('api_key')->nullable()->unique(); // Token untuk user H2H / API
			$table->string('jabber_id')->nullable(); // Alamat Jabber (sesuai pertanyaan awal Anda)
			$table->ipAddress('allowed_ip')->nullable(); // Whitelist IP untuk keamanan API

			$table->timestamp('email_verified_at')->nullable();
			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes(); // Agar data user tidak hilang permanen (penting untuk histori transaksi)
		});

		Schema::create('password_reset_tokens', function (Blueprint $table) {
			$table->string('email')->primary();
			$table->string('token');
			$table->timestamp('created_at')->nullable();
		});

		Schema::create('sessions', function (Blueprint $table) {
			$table->string('id')->primary();
			$table->foreignId('user_id')->nullable()->index();
			$table->string('ip_address', 45)->nullable();
			$table->text('user_agent')->nullable();
			$table->longText('payload');
			$table->integer('last_activity')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
		Schema::dropIfExists('password_reset_tokens');
		Schema::dropIfExists('sessions');
	}
};
