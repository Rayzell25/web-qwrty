<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara mengecek status invoice saya?',
                'answer' => 'Buka halaman "Cek Invoice", masukkan nomor invoice Anda, lalu klik tombol Cek. Sistem akan menampilkan detail invoice beserta status garansinya.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Bagaimana cara mengajukan klaim garansi?',
                'answer' => 'Kunjungi halaman "Klaim Garansi", lengkapi formulir dengan data diri, nomor invoice, dan keluhan Anda. Anda juga dapat melampirkan foto atau dokumen pendukung.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Berapa lama proses klaim garansi?',
                'answer' => 'Proses verifikasi klaim garansi umumnya memakan waktu 1-3 hari kerja. Tim kami akan menghubungi Anda melalui WhatsApp atau email.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Apakah saya harus membuat akun untuk berbelanja?',
                'answer' => 'Anda dapat menjelajahi katalog tanpa akun. Namun untuk fitur tertentu seperti riwayat klaim, disarankan untuk mendaftar dan memverifikasi OTP.',
                'sort_order' => 4,
            ],
            [
                'question' => 'Bagaimana cara menghubungi customer service?',
                'answer' => 'Anda dapat menghubungi kami melalui halaman Kontak, WhatsApp, atau email yang tertera di footer situs.',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'is_active' => true,
                    'sort_order' => $faq['sort_order'],
                ]
            );
        }
    }
}
