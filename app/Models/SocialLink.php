<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SocialLink extends Model
{
    /** @use HasFactory<\Database\Factories\SocialLinkFactory> */
    use HasFactory;

    /**
     * Platform yang didukung: label + ikon Bootstrap Icons + base URL untuk username.
     *
     * @var array<string, array{label: string, icon: string, base: string}>
     */
    public const PLATFORMS = [
        'telegram'  => ['label' => 'Telegram',    'icon' => 'bi-telegram',     'base' => 'https://t.me/'],
        'whatsapp'  => ['label' => 'WhatsApp',     'icon' => 'bi-whatsapp',     'base' => 'https://wa.me/'],
        'instagram' => ['label' => 'Instagram',    'icon' => 'bi-instagram',    'base' => 'https://instagram.com/'],
        'facebook'  => ['label' => 'Facebook',     'icon' => 'bi-facebook',     'base' => 'https://facebook.com/'],
        'tiktok'    => ['label' => 'TikTok',       'icon' => 'bi-tiktok',       'base' => 'https://tiktok.com/@'],
        'youtube'   => ['label' => 'YouTube',      'icon' => 'bi-youtube',      'base' => 'https://youtube.com/'],
        'twitter'   => ['label' => 'Twitter / X',  'icon' => 'bi-twitter-x',    'base' => 'https://x.com/'],
        'discord'   => ['label' => 'Discord',      'icon' => 'bi-discord',      'base' => 'https://'],
        'website'   => ['label' => 'Website',      'icon' => 'bi-globe2',       'base' => 'https://'],
        'email'     => ['label' => 'Email',        'icon' => 'bi-envelope-fill','base' => 'mailto:'],
    ];

    protected $fillable = [
        'platform',
        'label',
        'url',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Ambil tautan aktif untuk ditampilkan di footer (aman jika tabel belum ada).
     */
    public static function activeForDisplay(): Collection
    {
        try {
            return static::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    public function getIconAttribute(): string
    {
        return self::PLATFORMS[$this->platform]['icon'] ?? 'bi-link-45deg';
    }

    public function getPlatformLabelAttribute(): string
    {
        return self::PLATFORMS[$this->platform]['label'] ?? ucfirst((string) $this->platform);
    }

    /**
     * Bangun URL final dari input yang fleksibel:
     * - URL lengkap (https://...) dipakai apa adanya
     * - "t.me/xxx" / "facebook.com/xxx" (mengandung titik) → diberi https://
     * - username/handle ("@xxx" / "xxx") → digabung base platform
     * - WhatsApp nomor → https://wa.me/<digit>
     * - Email → mailto:
     */
    public function getHrefAttribute(): string
    {
        $url = trim((string) $this->url);
        $platform = $this->platform;
        $base = self::PLATFORMS[$platform]['base'] ?? 'https://';

        if ($url === '') {
            return '#';
        }

        if ($platform === 'email') {
            return str_starts_with($url, 'mailto:') ? $url : 'mailto:' . $url;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if ($platform === 'whatsapp' && preg_match('/^\+?[\d\s\-()]+$/', $url)) {
            return 'https://wa.me/' . preg_replace('/\D/', '', $url);
        }

        // Mengandung titik = sudah berupa domain/URL tanpa skema
        if (str_contains($url, '.')) {
            return 'https://' . ltrim($url, '/');
        }

        // Username/handle murni → gabung dengan base platform
        return $base . ltrim($url, '@/');
    }
}
