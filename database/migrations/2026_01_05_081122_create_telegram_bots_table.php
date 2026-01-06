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
		Schema::create('telegram_bots', function (Blueprint $table) {
			$table->id();
			$table->string('name'); // Contoh: "Bot Notifikasi Utama"
			$table->string('token')->unique(); // Token dari BotFather
			$table->string('default_chat_id'); // Chat ID tujuan default (Grup/Channel/Admin)
			$table->boolean('is_active')->default(true); // Status Aktif
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('telegram_bots');
	}
};
