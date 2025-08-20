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
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('barcode')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('discount')->nullable();
            $table->string('category')->default('general');
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();       // modelo único
            $table->string('color')->nullable();       // color único
            $table->string('size')->nullable();        // talla o tamaño único
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->string('material')->nullable();
            $table->json('features')->nullable();      // array de características
            $table->string('warranty')->nullable();
            $table->string('packaging')->nullable();
            $table->text('notes')->nullable();
            $table->json('media')->nullable();
            $table->enum('state', ['available', 'unavailable'])->default('available');
            $table->boolean('free_shipping')->default(false);
            $table->boolean('international')->default(false);
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->decimal('rating', 2, 1)->default(0);   // promedio de reseñas
            $table->unsignedBigInteger('sales_count')->default(0);
            $table->boolean('is_best_seller')->default(false);
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
