@php
    $siteName = setting('site_name', config('app.name', 'RPD'));

    /*
     * Sosial media dinamis — hanya muncul kalau URL-nya diisi di admin.
     * Tambah platform baru cukup dengan menambah baris di sini + key di Pengaturan Situs.
     *
     * Untuk WhatsApp: value diisi nomor (081xxx) ATAU URL lengkap (https://wa.me/628...)
     * Untuk Telegram: value diisi username (@channel) ATAU URL (https://t.me/...)
     */
    $socials = [];

    $platforms = [
        ['key' => 'facebook_url',  'icon' => 'bi-facebook',     'label' => 'Facebook',   'mode' => 'url'],
        ['key' => 'instagram_url', 'icon' => 'bi-instagram',    'label' => 'Instagram',  'mode' => 'url'],
        ['key' => 'tiktok_url',    'icon' => 'bi-tiktok',       'label' => 'TikTok',     'mode' => 'url'],
        ['key' => 'youtube_url',   'icon' => 'bi-youtube',      'label' => 'YouTube',    'mode' => 'url'],
        ['key' => 'twitter_url',   'icon' => 'bi-twitter-x',    'label' => 'Twitter/X',  'mode' => 'url'],
        ['key' => 'telegram_url',  'icon' => 'bi-telegram',     'label' => 'Telegram',   'mode' => 'telegram'],
        ['key' => 'whatsapp_url',  'icon' => 'bi-whatsapp',     'label' => 'WhatsApp',   'mode' => 'whatsapp'],
    ];

    foreach ($platforms as $p) {
        $val = setting($p['key'], '');
        if (blank($val)) continue;

        $href = match ($p['mode']) {
            'whatsapp' => (str_starts_with($val, 'http') ? $val : 'https://wa.me/' . preg_replace('/\D/', '', $val)),
            'telegram' => (str_starts_with($val, 'http') ? $val : 'https://t.me/' . ltrim($val, '@')),
            default => $val,
        };

        $socials[] = array_merge($p, ['href' => $href]);
    }

    // Fallback: kalau whatsapp_url kosong, coba company_whatsapp (nomor HP)
    $hasWa = collect($socials)->contains('key', 'whatsapp_url');
    if (! $hasWa) {
        $wa = setting('company_whatsapp', '');
        if (filled($wa)) {
            $socials[] = [
                'key' => 'company_whatsapp',
                'icon' => 'bi-whatsapp',
                'label' => 'WhatsApp',
                'href' => 'https://wa.me/' . preg_replace('/\D/', '', $wa),
            ];
        }
    }
@endphp

<footer class="site-footer pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            {{-- Brand & info --}}
            <div class="col-lg-4">
                <div class="foot-brand h4 mb-2">{{ $siteName }}</div>
                <p class="text-secondary mb-3" style="max-width: 320px;">
                    {{ setting('site_tagline', 'Produk berkualitas dengan garansi resmi.') }}
                </p>
                @if ($address = setting('company_address'))
                    <p class="mb-1 small"><i class="bi bi-geo-alt me-2"></i>{{ $address }}</p>
                @endif
                @if ($phone = setting('company_phone'))
                    <p class="mb-1 small"><i class="bi bi-telephone me-2"></i>{{ $phone }}</p>
                @endif
                @if ($email = setting('company_email'))
                    <p class="mb-1 small"><i class="bi bi-envelope me-2"></i>{{ $email }}</p>
                @endif
            </div>

            {{-- Navigasi --}}
            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Navigasi</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                    <li><a href="{{ route('leaderboard.index') }}">Leaderboard</a></li>
                    <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                </ul>
            </div>

            {{-- Layanan --}}
            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Layanan</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('invoice.index') }}">Cek Invoice</a></li>
                    <li><a href="{{ route('warranty.index') }}">Klaim Garansi</a></li>
                    <li><a href="{{ route('contact.index') }}">Kontak</a></li>
                </ul>
            </div>

            {{-- Ikuti Kami — hanya tampil kalau ada minimal 1 sosmed diisi --}}
            @if (count($socials) > 0)
                <div class="col-lg-4">
                    <h6 class="foot-brand mb-3">Ikuti Kami</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($socials as $s)
                            <a class="soc"
                               href="{{ $s['href'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="{{ $s['label'] }}"
                               title="{{ $s['label'] }}">
                                <i class="bi {{ $s['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-secondary small mt-3 mb-0">
                        Ikuti kami di media sosial untuk update terbaru.
                    </p>
                </div>
            @endif
        </div>

        <hr class="my-4" style="border-color: rgba(255,255,255,.08);">

        <p class="text-center text-secondary small mb-0">
            {{ setting('footer_text', '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.') }}
        </p>
    </div>
</footer>
