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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->string('slug')->unique();

            // $table->string('category');

            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            // Status Brand (1 = Aktif, 0 = Nonaktif)
            $table->boolean('status')->default(true);
            $table->timestamps();

			// $table->unique(['slug', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
