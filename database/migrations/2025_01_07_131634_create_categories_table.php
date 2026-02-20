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
		Schema::create('categories', function (Blueprint $table) {
			$table->id();
			// Parent ID untuk Sub-kategori (misal: Elektronik -> HP)
			// Jika dihapus, anak kategorinya jadi NULL (tidak hilang)
			$table->foreignId('parent_id')
				->nullable()
				->constrained('categories')
				->nullOnDelete();

			$table->string('name');
			$table->string('slug')->unique();
			$table->string('image')->nullable();
			$table->string('icon')->nullable();

			// Urutan prioritas tampilan (opsional tapi berguna)
			$table->integer('sort_order')->default(0);

			$table->boolean('status')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('categories');
	}
};
