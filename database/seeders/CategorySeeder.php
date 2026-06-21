<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Berbagai perangkat elektronik berkualitas.', 'sort_order' => 1],
            ['name' => 'Aksesoris', 'description' => 'Aksesoris pelengkap kebutuhan Anda.', 'sort_order' => 2],
            ['name' => 'Gaming', 'description' => 'Perlengkapan gaming untuk performa maksimal.', 'sort_order' => 3],
            ['name' => 'Rumah Tangga', 'description' => 'Produk kebutuhan rumah tangga sehari-hari.', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ]
            );
        }
    }
}
