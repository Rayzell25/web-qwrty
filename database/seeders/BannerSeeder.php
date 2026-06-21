<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Produk Berkualitas, Garansi Resmi',
                'subtitle' => 'Belanja aman dengan jaminan kualitas dan layanan purna jual terbaik.',
                'button_text' => 'Lihat Produk',
                'button_link' => '/products',
                'sort_order' => 1,
            ],
            [
                'title' => 'Klaim Garansi Mudah & Cepat',
                'subtitle' => 'Ajukan klaim garansi Anda secara online kapan saja.',
                'button_text' => 'Klaim Sekarang',
                'button_link' => '/warranty-claim',
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                [
                    'subtitle' => $banner['subtitle'],
                    'button_text' => $banner['button_text'],
                    'button_link' => $banner['button_link'],
                    'is_active' => true,
                    'sort_order' => $banner['sort_order'],
                ]
            );
        }
    }
}
