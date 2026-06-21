<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            [
                'category' => 'elektronik',
                'name' => 'Smartphone Pro X',
                'short_description' => 'Smartphone flagship dengan kamera 108MP dan baterai tahan lama.',
                'description' => "Smartphone Pro X hadir dengan layar AMOLED 6.7 inci, chipset terbaru, dan sistem kamera triple lens.\nDidukung baterai 5000mAh dengan fast charging 65W.",
                'specification' => "Layar: 6.7\" AMOLED 120Hz\nRAM: 12GB\nStorage: 256GB\nBaterai: 5000mAh\nKamera: 108MP + 12MP + 8MP",
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'elektronik',
                'name' => 'Laptop UltraBook 14',
                'short_description' => 'Laptop tipis dan ringan untuk produktivitas tinggi.',
                'description' => "Laptop UltraBook 14 dengan bodi aluminium premium, prosesor hemat daya, dan layar 2K.",
                'specification' => "Prosesor: Core i7\nRAM: 16GB\nSSD: 512GB\nLayar: 14\" 2K IPS\nBerat: 1.2kg",
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category' => 'aksesoris',
                'name' => 'Wireless Earbuds Air',
                'short_description' => 'Earbuds nirkabel dengan noise cancelling aktif.',
                'description' => "Earbuds dengan ANC, latensi rendah, dan daya tahan hingga 30 jam dengan charging case.",
                'specification' => "Bluetooth: 5.3\nANC: Ya\nBaterai: 6 jam (30 jam dengan case)\nIPX4 Water Resistant",
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'aksesoris',
                'name' => 'Power Bank 20000mAh',
                'short_description' => 'Power bank kapasitas besar dengan fast charging.',
                'description' => "Power bank 20000mAh dengan dukungan PD 22.5W dan dua port output.",
                'specification' => "Kapasitas: 20000mAh\nOutput: USB-C PD 22.5W\nInput: USB-C",
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'category' => 'gaming',
                'name' => 'Mechanical Keyboard RGB',
                'short_description' => 'Keyboard mekanikal dengan switch hot-swappable.',
                'description' => "Keyboard gaming mekanikal dengan RGB per-key, switch hot-swappable, dan body aluminium.",
                'specification' => "Switch: Hot-swappable\nLayout: TKL\nKoneksi: USB-C / Bluetooth\nRGB: Per-key",
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'gaming',
                'name' => 'Gaming Mouse Pro',
                'short_description' => 'Mouse gaming ringan dengan sensor 26K DPI.',
                'description' => "Mouse gaming dengan sensor presisi tinggi, bobot ringan, dan polling rate 1000Hz.",
                'specification' => "Sensor: 26000 DPI\nBerat: 59g\nPolling: 1000Hz\nKoneksi: Wireless",
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'category' => 'rumah-tangga',
                'name' => 'Air Fryer 5L',
                'short_description' => 'Air fryer kapasitas keluarga dengan layar digital.',
                'description' => "Air fryer 5 liter dengan kontrol digital, 8 preset memasak, dan teknologi rapid air.",
                'specification' => "Kapasitas: 5L\nDaya: 1500W\nKontrol: Digital touch\nPreset: 8 menu",
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'rumah-tangga',
                'name' => 'Robot Vacuum Cleaner',
                'short_description' => 'Penyedot debu robot dengan navigasi pintar.',
                'description' => "Robot vacuum dengan pemetaan LiDAR, mode mopping, dan kontrol via aplikasi.",
                'specification' => "Daya Hisap: 4000Pa\nNavigasi: LiDAR\nBaterai: 5200mAh\nMopping: Ya",
                'is_featured' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($products as $item) {
            $category = $categories->get($item['category']);

            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'specification' => $item['specification'],
                    'is_featured' => $item['is_featured'],
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }
}
