<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        // Categorías principales compatibles con el controlador
        $categoriesData = [
            'smartphones' => [
                'subcategories' => ['smartphones', 'flagship', 'mid_range', 'budget'],
                'brands' => ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'OnePlus', 'Google', 'Oppo', 'Vivo'],
                'colors' => ['Negro', 'Blanco', 'Azul', 'Rosa', 'Verde', 'Morado', 'Dorado'],
                'sizes' => ['5.5"', '6.1"', '6.4"', '6.7"', '6.8"'],
                'features' => ['5G', 'Cámara triple', 'Resistente al agua', 'Carga rápida', 'Face ID', 'Pantalla OLED']
            ],
            'cellphones' => [
                'subcategories' => ['basic_phone', 'feature_phone', 'rugged_phone'],
                'brands' => ['Nokia', 'Samsung', 'Alcatel', 'Cat', 'Kyocera'],
                'colors' => ['Negro', 'Azul', 'Rojo'],
                'sizes' => ['2.4"', '2.8"', '3.2"'],
                'features' => ['Batería larga duración', 'Radio FM', 'Linterna', 'Resistente a golpes']
            ],
            'tablets' => [
                'subcategories' => ['ipad', 'android_tablets', 'windows_tablets'],
                'brands' => ['Apple', 'Samsung', 'Lenovo', 'Huawei', 'Microsoft'],
                'colors' => ['Gris espacial', 'Plata', 'Oro', 'Negro'],
                'sizes' => ['8"', '10.1"', '10.9"', '11"', '12.9"'],
                'features' => ['Wi-Fi + Cellular', 'Apple Pencil compatible', 'Teclado magnético', 'Pantalla Retina']
            ],
            'accessories' => [
                'subcategories' => ['mobile_accessories', 'tablet_accessories', 'drone_accessories', 'camera_accessories', 'audio_accessories', 'tv_accessories'],
                'brands' => ['Anker', 'Belkin', 'Spigen', 'OtterBox', 'UAG', 'Baseus', 'DJI', 'GoPro'],
                'colors' => ['Negro', 'Blanco', 'Azul', 'Rosa', 'Rojo'],
                'sizes' => ['Universal', 'Pequeño', 'Mediano', 'Grande'],
                'features' => ['Protección', 'Carga inalámbrica', 'Anti-rayones', 'Magnético']
            ],
            'drones' => [
                'subcategories' => ['recreational_drones', 'professional_drones'],
                'brands' => ['DJI', 'Autel', 'Parrot'],
                'colors' => ['Negro', 'Blanco', 'Gris', 'Naranja'],
                'sizes' => ['Compacta', 'Mediana', 'Profesional'],
                'features' => ['4K', '8K', 'Estabilización gimbal', 'Seguimiento automático', 'Resistente al agua']
            ],
            'cameras' => [
                'subcategories' => ['dslr_cameras', 'mirrorless_cameras', 'action_cameras'],
                'brands' => ['Canon', 'Sony', 'Nikon', 'Fujifilm', 'GoPro'],
                'colors' => ['Negro', 'Plata', 'Gris'],
                'sizes' => ['Compacta', 'Mediana', 'Profesional'],
                'features' => ['4K', 'Estabilización', 'Zoom óptico', 'Wi-Fi integrado']
            ],
            'headphones' => [
                'subcategories' => ['headphones', 'earbuds'],
                'brands' => ['Sony', 'Bose', 'JBL', 'Sennheiser', 'Beats'],
                'colors' => ['Negro', 'Blanco', 'Azul', 'Rojo', 'Rosa'],
                'sizes' => ['Over-ear', 'On-ear', 'In-ear'],
                'features' => ['Cancelación de ruido', 'Bluetooth 5.0', 'Hi-Res Audio', 'Batería 30h', 'Resistente al agua']
            ],
            'speakers' => [
                'subcategories' => ['speakers', 'soundbars'],
                'brands' => ['Sony', 'Bose', 'JBL', 'Samsung', 'LG'],
                'colors' => ['Negro', 'Blanco', 'Gris'],
                'sizes' => ['Pequeño', 'Mediano', 'Grande'],
                'features' => ['Bluetooth', 'Wi-Fi', 'Resistente al agua', 'Subwoofer incluido']
            ],
            'tv' => [
                'subcategories' => ['tv', 'home_cinema', 'projectors', 'soundbars'],
                'brands' => ['Samsung', 'LG', 'Sony', 'Philips', 'Panasonic'],
                'colors' => ['Negro', 'Plata', 'Gris'],
                'sizes' => ['32"', '40"', '50"', '55"', '65"', '75"'],
                'features' => ['4K', '8K', 'HDR', 'Smart TV', 'OLED']
            ],
            'home_cinema' => [
                'subcategories' => ['home_cinema'],
                'brands' => ['Sony', 'Bose', 'LG', 'Samsung'],
                'colors' => ['Negro', 'Gris'],
                'sizes' => ['Mediano', 'Grande'],
                'features' => ['Dolby Atmos', 'Bluetooth', 'Subwoofer', '5.1 canales']
            ],
            'projectors' => [
                'subcategories' => ['projectors'],
                'brands' => ['Epson', 'BenQ', 'Optoma', 'LG'],
                'colors' => ['Blanco', 'Negro'],
                'sizes' => ['Compacto', 'Mediano', 'Profesional'],
                'features' => ['HD', '4K', 'Portátil', 'Lámpara LED']
            ],
        ];

        $categoryKey = $this->faker->randomElement(array_keys($categoriesData));
        $categoryData = $categoriesData[$categoryKey];

        $subcategory = $this->faker->randomElement($categoryData['subcategories']);
        $brand = $this->faker->randomElement($categoryData['brands']);
        $color = $this->faker->randomElement($categoryData['colors']);
        $size = $this->faker->randomElement($categoryData['sizes']);

        $name = "$brand " . $subcategory . " " . $this->faker->word();

        $priceRange = $this->getPriceRange($categoryKey);
        $originalPrice = $this->faker->randomFloat(0, $priceRange['min'], $priceRange['max']);
        $discount = $this->faker->numberBetween(0, 40);
        $unitPrice = round($originalPrice * (1 - $discount / 100), 0);

        return [
            'branch_id' => $this->faker->numberBetween(1, 3),
            'name' => $name,
            'description' => $this->faker->sentence(15),
            'barcode' => $this->faker->ean13(),
            'unit_price' => $unitPrice,
            'original_price' => $originalPrice,
            'discount' => $discount > 0 ? $discount : null,
            'category' => $categoryKey,
            'subcategory' => $subcategory,
            'brand' => $brand,
            'model' => strtoupper($this->faker->bothify('??-###')),
            'color' => $color,
            'size' => $size,
            'features' => json_encode($this->faker->randomElements($categoryData['features'], $this->faker->numberBetween(2,4))),
            'media' => json_encode([$this->faker->imageUrl(600, 600)]),
            'state' => $this->faker->randomElement(['available', 'unavailable']),
            'free_shipping' => $this->faker->boolean(60),
            'shipping_price' => $this->faker->randomFloat(0, 5000, 25000),
            'rating' => $this->faker->randomFloat(1, 3.5, 5.0),
            'sales_count' => $this->faker->numberBetween(0, 1000),
        ];
    }

    private function getPriceRange($category)
    {
        $ranges = [
            'smartphones' => ['min' => 300000, 'max' => 5000000],
            'cellphones' => ['min' => 50000, 'max' => 300000],
            'tablets' => ['min' => 400000, 'max' => 3000000],
            'accessories' => ['min' => 15000, 'max' => 200000],
            'drones' => ['min' => 200000, 'max' => 8000000],
            'cameras' => ['min' => 500000, 'max' => 5000000],
            'headphones' => ['min' => 80000, 'max' => 1500000],
            'speakers' => ['min' => 100000, 'max' => 2500000],
            'tv' => ['min' => 1000000, 'max' => 15000000],
            'home_cinema' => ['min' => 500000, 'max' => 5000000],
            'projectors' => ['min' => 500000, 'max' => 3000000],
        ];

        return $ranges[$category] ?? ['min' => 50000, 'max' => 1000000];
    }
}
