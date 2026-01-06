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
		Schema::create('providers', function (Blueprint $table) {
			$table->id();
			$table->string('code')->unique()->comment('contoh:  digiflazz, vip, apigames');
			$table->string('name')->comment('nama provider');
			$table->string('api_username')->nullable();
			$table->text('api_key')->nullable();
			$table->text('secret_key')->nullable()->comment('untuk webhook (sign)');
			$table->decimal('balance', 15, 2)->default(0);
			$table->enum('mode', ['development', 'production'])->default('development');
			$table->string('base_url')->nullable()->comment('base_url dari provider');
			$table->boolean('is_active')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('providers');
	}
};
