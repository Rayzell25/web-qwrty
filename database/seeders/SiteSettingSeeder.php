<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // group: general
            ['key' => 'site_name', 'value' => 'RPD', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Solusi produk terpercaya untuk Anda', 'group' => 'general'],
            ['key' => 'logo', 'value' => null, 'group' => 'general'],
            ['key' => 'favicon', 'value' => null, 'group' => 'general'],
            ['key' => 'footer_text', 'value' => '© '.date('Y').' RPD. All rights reserved.', 'group' => 'general'],

            // group: hero
            ['key' => 'hero_title', 'value' => 'Selamat Datang di RPD', 'group' => 'hero'],
            ['key' => 'hero_subtitle', 'value' => 'Temukan produk terbaik dengan kualitas terjamin dan garansi resmi.', 'group' => 'hero'],

            // group: contact
            ['key' => 'company_address', 'value' => 'Jl. Merdeka No. 123, Jakarta, Indonesia', 'group' => 'contact'],
            ['key' => 'company_phone', 'value' => '021-1234567', 'group' => 'contact'],
            ['key' => 'company_whatsapp', 'value' => '6281200000000', 'group' => 'contact'],
            ['key' => 'company_email', 'value' => 'info@rpd.local', 'group' => 'contact'],

            // group: social
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/', 'group' => 'social'],
            ['key' => 'tiktok_url', 'value' => 'https://tiktok.com/', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/', 'group' => 'social'],

            // group: seo
            ['key' => 'meta_title', 'value' => 'RPD — Solusi produk terpercaya', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'RPD menyediakan produk berkualitas dengan garansi resmi dan layanan terbaik.', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
