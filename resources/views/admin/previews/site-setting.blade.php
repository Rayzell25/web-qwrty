@php
    use Illuminate\Support\Facades\Storage;

    $key = $setting->key;
    $val = (string) $setting->value;

    $imgUrl = null;
    if (filled($val)) {
        $imgUrl = (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))
            ? $val
            : Storage::disk('public')->url($val);
    }

    $locations = [
        'logo' => 'Logo di navbar (kiri atas) & footer',
        'favicon' => 'Ikon kecil di tab browser',
        'site_name' => 'Nama brand di navbar & footer',
        'site_tagline' => 'Tagline di footer (di bawah nama)',
        'hero_title' => 'Judul besar hero halaman depan (baris 1)',
        'hero_title_accent' => 'Judul hero baris 2 (warna gradient)',
        'hero_subtitle' => 'Teks di bawah judul hero',
        'footer_text' => 'Teks copyright paling bawah',
        'social_heading' => 'Judul section sosial di footer',
        'company_address' => 'Alamat di footer & halaman Kontak',
        'company_phone' => 'Telepon di footer & Kontak',
        'company_whatsapp' => 'WhatsApp di halaman Kontak',
        'company_email' => 'Email di footer & Kontak',
        'meta_title' => 'Judul SEO (title tab & hasil Google)',
        'meta_description' => 'Deskripsi SEO (hasil Google)',
    ];
    $loc = $locations[$key] ?? 'Pengaturan umum situs';
    $isImage = in_array($key, ['logo', 'favicon'], true);
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div style="padding: 4px;">
    <p style="color:#6b7280; font-size:.82rem; margin-bottom:12px;">Preview hasil yang akan tampil di website:</p>

    <div style="background:#f6f7fb; border:1px solid #e8eaf1; border-radius:16px; padding:28px; text-align:center;">
        @if ($isImage)
            @if ($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $key }}"
                     style="max-height:96px; max-width:100%; object-fit:contain;">
            @else
                <span style="color:#9ca3af; font-style:italic;">Belum ada file diupload</span>
            @endif
        @elseif (in_array($key, ['hero_title', 'hero_title_accent']))
            <span style="font-weight:800; font-size:1.8rem; color:#0f172a; letter-spacing:-.02em;">{{ $val ?: '—' }}</span>
        @elseif ($key === 'site_name')
            <span style="font-weight:800; font-size:1.4rem; color:#4f46e5;">{{ $val ?: '—' }}</span>
        @else
            <span style="color:#0f172a; font-size:1.05rem;">{{ $val !== '' ? $val : '—' }}</span>
        @endif
    </div>

    <div style="margin-top:14px; display:flex; gap:8px; align-items:flex-start; color:#6b7280; font-size:.85rem;">
        <i class="bi bi-geo-alt" style="color:#4f46e5;"></i>
        <span>Muncul di: <strong style="color:#374151;">{{ $loc }}</strong></span>
    </div>
</div>
