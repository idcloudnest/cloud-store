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
		Schema::create('balance_histories', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->onDelete('cascade');
			$table->enum('type', ['debit', 'credit']); // debit = uang masuk, credit = uang keluar
			$table->decimal('amount', 15, 2); // Jumlah uang
			$table->string('description'); // Contoh: "Refund Transaksi INV-123"
			$table->decimal('last_balance', 15, 2); // Saldo akhir setelah transaksi ini
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('balance_histories');
	}
};
