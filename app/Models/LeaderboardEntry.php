<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    /** @use HasFactory<\Database\Factories\LeaderboardEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'score',
        'rank',
        'city',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'rank' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
