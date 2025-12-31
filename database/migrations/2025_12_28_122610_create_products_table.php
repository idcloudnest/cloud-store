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
		Schema::create('products', function (Blueprint $table) {
			$table->id();

			$table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
			$table->string('product_name');
			$table->string('category', 50);
			$table->string('brand', 50);
			$table->string('type', 50);

			$table->string('seller_name');

			$table->decimal('price', 15, 2);

			// Kode SKU (biasanya unique)
			$table->string('buyer_sku_code')->unique();

			// Status
			$table->boolean('buyer_product_status')->default(true);
			$table->boolean('seller_product_status')->default(true);

			// Stok
			$table->boolean('unlimited_stock')->default(false);
			$table->unsignedInteger('stock')->default(0);

			// Multi transaksi
			$table->boolean('multi')->default(false);

			// Jam cut off
			$table->time('start_cut_off')->nullable();
			$table->time('end_cut_off')->nullable();

			// Deskripsi
			$table->string('description')->nullable();
			$table->timestamps();
		});
	}

	/**
	* Reverse the migrations.
	*/
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
