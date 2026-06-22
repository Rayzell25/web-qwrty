<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menambahkan key sosmed baru (telegram_url, whatsapp_url, twitter_url)
 * ke tabel site_settings jika belum ada.
 * Aman dijalankan berkali-kali (INSERT OR IGNORE / updateOrCreate).
 */
return new class extends Migration
{
    public function up(): void
    {
        $new = [
            ['key' => 'telegram_url',  'value' => '', 'group' => 'social'],
            ['key' => 'whatsapp_url',  'value' => '', 'group' => 'social'],
            ['key' => 'twitter_url',   'value' => '', 'group' => 'social'],
        ];

        foreach ($new as $row) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', ['telegram_url', 'whatsapp_url', 'twitter_url'])
            ->delete();
    }
};
