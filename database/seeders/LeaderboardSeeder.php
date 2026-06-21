<?php

namespace Database\Seeders;

use App\Models\LeaderboardEntry;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            ['name' => 'Andi Pratama', 'score' => 9800, 'rank' => 1, 'city' => 'Jakarta'],
            ['name' => 'Budi Santoso', 'score' => 9450, 'rank' => 2, 'city' => 'Surabaya'],
            ['name' => 'Citra Dewi', 'score' => 9100, 'rank' => 3, 'city' => 'Bandung'],
            ['name' => 'Dani Hermawan', 'score' => 8700, 'rank' => 4, 'city' => 'Medan'],
            ['name' => 'Eka Putri', 'score' => 8300, 'rank' => 5, 'city' => 'Semarang'],
            ['name' => 'Fajar Nugroho', 'score' => 7950, 'rank' => 6, 'city' => 'Yogyakarta'],
            ['name' => 'Gita Lestari', 'score' => 7600, 'rank' => 7, 'city' => 'Makassar'],
            ['name' => 'Hadi Wijaya', 'score' => 7200, 'rank' => 8, 'city' => 'Denpasar'],
        ];

        foreach ($entries as $entry) {
            LeaderboardEntry::updateOrCreate(
                ['name' => $entry['name']],
                [
                    'score' => $entry['score'],
                    'rank' => $entry['rank'],
                    'city' => $entry['city'],
                    'is_active' => true,
                ]
            );
        }
    }
}
