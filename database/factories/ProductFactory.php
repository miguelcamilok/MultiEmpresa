<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $rating = $this->faker->randomFloat(1, 1, 5); // calificación entre 1 y 5
        $salesCount = $this->faker->numberBetween(0, 500); // número de ventas

        return [
            'branch_id' => 1,
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph,
            'barcode' => $this->faker->ean13,
            'unit_price' => $this->faker->randomFloat(2, 100, 5000),
            'media' => $this->faker->imageUrl(400, 400, 'technics', true),
            'state' => 'available',

            // Nuevos campos
            'rating' => $rating,
            'sales_count' => $salesCount,
            // Se marca como Best Seller solo si cumple los criterios
            'is_best_seller' => ($rating >= 4.7 && $salesCount >= 50),
        ];
    }
}
