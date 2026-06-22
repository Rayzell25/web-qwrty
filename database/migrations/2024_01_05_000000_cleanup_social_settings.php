<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Bersihkan key sosmed lama (yang dulu hardcoded di footer) dari site_settings,
 * karena sekarang tautan sosial dikelola lewat tabel social_links.
 * Tambahkan key 'social_heading' (judul section, default kosong = tidak tampil).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'facebook_url', 'instagram_url', 'tiktok_url', 'youtube_url',
            'twitter_url', 'telegram_url', 'whatsapp_url',
        ])->delete();

        DB::table('site_settings')->updateOrInsert(
            ['key' => 'social_heading'],
            ['value' => '', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        // Tidak mengembalikan key lama (tidak perlu).
    }
};
