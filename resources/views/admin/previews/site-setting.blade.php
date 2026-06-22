@php
    use Illuminate\Support\Facades\Storage;

    $key = $setting->key;
    $val = (string) $setting->value;

    $resolveAsset = function (?string $v): ?string {
        if (blank($v)) return null;
        return (str_starts_with($v, 'http://') || str_starts_with($v, 'https://'))
            ? $v
            : Storage::disk('public')->url($v);
    };

    $imgUrl    = $resolveAsset($val);
    $siteName  = setting('site_name', config('app.name', 'RPD'));
    $logoUrl   = $resolveAsset(setting('logo'));

    $locations = [
        'logo' => 'Navbar (kiri atas) & footer',
        'favicon' => 'Ikon kecil di tab browser',
        'site_name' => 'Navbar & footer',
        'site_tagline' => 'Footer (di bawah nama)',
        'hero_title' => 'Hero halaman depan',
        'hero_title_accent' => 'Hero halaman depan (baris gradient)',
        'hero_subtitle' => 'Hero halaman depan',
        'footer_text' => 'Footer paling bawah',
        'social_heading' => 'Footer (judul sosial)',
        'company_address' => 'Footer & halaman Kontak',
        'company_phone' => 'Footer & halaman Kontak',
        'company_whatsapp' => 'Footer & halaman Kontak',
        'company_email' => 'Footer & halaman Kontak',
        'meta_title' => 'Judul tab & hasil pencarian Google',
        'meta_description' => 'Deskripsi di hasil pencarian Google',
    ];
    $loc = $locations[$key] ?? 'Pengaturan umum situs';

    $contactIcons = [
        'company_address'  => 'bi-geo-alt',
        'company_phone'    => 'bi-telephone',
        'company_whatsapp' => 'bi-whatsapp',
        'company_email'    => 'bi-envelope',
    ];
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div style="padding:4px;">
    <p style="color:#6b7280; font-size:.82rem; margin-bottom:12px;">Beginilah hasilnya di website (bagian ini saja):</p>

    @switch(true)
        {{-- ===== LOGO ===== --}}
        @case($key === 'logo')
            <div style="background:#fff; border:1px solid #e8eaf1; border-radius:14px; padding:14px 18px; display:flex; align-items:center; gap:12px; box-shadow:0 10px 30px -14px rgba(20,26,46,.25);">
                @if ($imgUrl)
                    <img src="{{ $imgUrl }}" alt="logo" style="height:40px; max-width:170px; object-fit:contain;">
                @else
                    <span style="color:#9ca3af; font-style:italic;">Belum ada logo</span>
                @endif
                <span style="margin-left:auto; color:#c2c7d2; font-size:.8rem;">Beranda · Produk · Kontak</span>
            </div>
            @break

        {{-- ===== FAVICON ===== --}}
        @case($key === 'favicon')
            <div style="background:#e8eaf1; border-radius:10px 10px 0 0; padding:10px 14px; display:inline-flex; align-items:center; gap:8px; border:1px solid #d2d6e0; border-bottom:none;">
                @if ($imgUrl)
                    <img src="{{ $imgUrl }}" alt="favicon" style="width:18px; height:18px; object-fit:contain;">
                @else
                    <span style="width:18px;height:18px;background:#cbd2e0;border-radius:4px;display:inline-block;"></span>
                @endif
                <span style="color:#374151; font-size:.85rem;">{{ \Illuminate\Support\Str::limit($siteName, 18) }}</span>
                <i class="bi bi-x" style="color:#9ca3af;"></i>
            </div>
            @break

        {{-- ===== NAMA WEB ===== --}}
        @case($key === 'site_name')
            <div style="background:#fff; border:1px solid #e8eaf1; border-radius:14px; padding:14px 18px; display:flex; align-items:center; gap:10px; box-shadow:0 10px 30px -14px rgba(20,26,46,.25);">
                <span style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#4f46e5,#7c3aed);"></span>
                <span style="font-weight:800; font-size:1.15rem; color:#141a2e;">{{ $val ?: '—' }}</span>
            </div>
            @break

        {{-- ===== HERO ===== --}}
        @case(in_array($key, ['hero_title', 'hero_title_accent', 'hero_subtitle']))
            <div style="background:linear-gradient(180deg,#eef2ff,#f5f3ff 60%,#fff); border:1px solid #e8eaf1; border-radius:14px; padding:30px; text-align:center;">
                <h3 style="font-weight:800; font-size:1.7rem; color:#0f172a; letter-spacing:-.02em; margin:0;">
                    {{ setting('hero_title', 'Belanja Aman,') }}<br>
                    <span style="background:linear-gradient(120deg,#2563eb,#7c3aed); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">
                        {{ setting('hero_title_accent', 'Garansi Terpercaya') }}
                    </span>
                </h3>
                <p style="color:#64748b; margin:12px 0 0; font-size:.95rem;">{{ setting('hero_subtitle', '') }}</p>
            </div>
            @break

        {{-- ===== KONTAK (address/phone/whatsapp/email) ===== --}}
        @case(array_key_exists($key, $contactIcons))
            <div style="background:#0c1023; border-radius:14px; padding:22px;">
                <div style="color:#fff; font-weight:800; margin-bottom:10px;">{{ $siteName }}</div>
                <div style="display:flex; align-items:center; gap:10px; color:#cbd2e0; font-size:.95rem;">
                    <i class="bi {{ $contactIcons[$key] }}" style="color:#818cf8; font-size:1.1rem;"></i>
                    <span>{{ $val ?: '—' }}</span>
                </div>
            </div>
            @break

        {{-- ===== SOCIAL HEADING ===== --}}
        @case($key === 'social_heading')
            <div style="background:#0c1023; border-radius:14px; padding:22px;">
                <div style="color:#fff; font-weight:800; margin-bottom:12px;">{{ $val ?: 'Ikuti Kami' }}</div>
                <div style="display:flex; gap:8px;">
                    @foreach (['bi-telegram','bi-whatsapp','bi-instagram'] as $ic)
                        <span style="width:40px;height:40px;border-radius:11px;background:rgba(255,255,255,.06);color:#fff;display:grid;place-items:center;"><i class="bi {{ $ic }}"></i></span>
                    @endforeach
                </div>
            </div>
            @break

        {{-- ===== FOOTER TEXT ===== --}}
        @case($key === 'footer_text')
            <div style="background:#0c1023; border-radius:14px; padding:18px; text-align:center; color:#9aa4c0; font-size:.85rem;">
                {{ $val ?: '© ' . date('Y') . ' ' . $siteName }}
            </div>
            @break

        {{-- ===== SEO (Google result) ===== --}}
        @case(in_array($key, ['meta_title', 'meta_description']))
            <div style="background:#fff; border:1px solid #e8eaf1; border-radius:14px; padding:18px; text-align:left;">
                <div style="color:#1a0dab; font-size:1.15rem; line-height:1.3;">{{ setting('meta_title', $siteName) }}</div>
                <div style="color:#006621; font-size:.82rem; margin:2px 0 4px;">https://{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'rayzellstores.web.id' }}</div>
                <div style="color:#545454; font-size:.9rem;">{{ setting('meta_description', '') }}</div>
            </div>
            @break

        {{-- ===== TAGLINE / default ===== --}}
        @default
            <div style="background:#0c1023; border-radius:14px; padding:22px;">
                <div style="color:#fff; font-weight:800;">{{ $siteName }}</div>
                <div style="color:#9aa4c0; font-size:.92rem; margin-top:4px;">{{ $val ?: '—' }}</div>
            </div>
    @endswitch

    <div style="margin-top:14px; display:flex; gap:8px; align-items:flex-start; color:#6b7280; font-size:.85rem;">
        <i class="bi bi-geo-alt" style="color:#4f46e5;"></i>
        <span>Muncul di: <strong style="color:#374151;">{{ $loc }}</strong></span>
    </div>
</div>
