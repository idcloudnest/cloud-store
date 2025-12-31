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
		Schema::create('digiflazz_credentials', function (Blueprint $table) {
			$table->id();
			$table->string('username');
			$table->string('api_key');
			$table->enum('mode', ['development', 'production']);
			$table->string('base_url')->default('https://api.digiflazz.com/v1');
			$table->boolean('is_active')->default(true);
			$table->timestamps();
			$table->unique(['username', 'mode']);
		});
	}

	/**
	* Reverse the migrations.
	*/
	public function down(): void
	{
		Schema::dropIfExists('digiflazz_credentials');
	}
};
