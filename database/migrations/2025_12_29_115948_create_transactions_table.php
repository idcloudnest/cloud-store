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
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();

			// 1. Identitas Transaksi
			$table->string('invoice')->unique()->comment("IDCS-ymd-ABCDEF");
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment("Jika user dihapus, transaksi jangan hilang");
			$table->string('phone_number')->nullable()->comment("No WhatsApp untuk notifikasi");
			$table->string('customer_no')->comment("No HP / ID Game Pembeli");
			$table->string('zone_id')->nullable()->comment("Zone ID Game (Opsional)");

			// 2. Data Produk (SNAPSHOT)
			// Relasi dibuat nullable & set null on delete agar riwayat aman meski produk master dihapus
			$table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
			$table->string('product_name_snapshot')->comment("Nama produk saat dibeli (jaga-jaga jika nama di master berubah)");
			$table->string('sku_snapshot')->comment("SKU saat dibeli");

			// 3. Rincian Pembayaran (FINANCIAL)
			$table->string('payment_method')->nullable()->comment("Contoh: 'QRIS', 'BCA_VA', 'DANA'");

			// Kita butuh modal untuk hitung profit
			$table->decimal('buy_price', 15, 2)->default(0)->comment("Harga MODAL dari Digiflazz saat transaksi terjadi");

			$table->decimal('amount', 15, 2)->comment("Harga JUAL dasar ke user (buy_price + untung anda)");
			$table->decimal('admin_fee', 15, 2)->default(0)->comment("biaya layanan payment gateway");
			$table->decimal('unique_code', 8, 0)->default(0)->comment("kode unik (jika transfer manual)");
			$table->decimal('total_amount', 15, 2)->comment("Total = Amount + Fee + Kode Unik");

			// 4. Status
			$table->enum('status', ['pending', 'paid', 'processing', 'success', 'failed', 'expired'])
				->default('pending')
				->index();

			// 5. Data Provider (Digiflazz/VIP)
			$table->string('ref_id')->nullable()->comment("ID referensi kita ke provider (generate by sistem)");
			$table->string('sn')->nullable()->comment("bukti / token listrik / serial number topup");
			$table->text('provider_message')->nullable()->comment("alasan gagal (misal: nomor salah");

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('transactions');
	}
};
