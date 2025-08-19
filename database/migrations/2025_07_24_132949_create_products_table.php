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
            $table->text('barcode')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->text('media')->nullable();
            $table->enum('state', ['available', 'unavailable'])->default('available');

            // Nuevos campos para Best Sellers
            $table->decimal('rating', 2, 1)->default(0); // promedio de reseñas
            $table->unsignedBigInteger('sales_count')->default(0); // número de ventas
            $table->boolean('is_best_seller')->default(false); // opcional, para marcar manualmente

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
