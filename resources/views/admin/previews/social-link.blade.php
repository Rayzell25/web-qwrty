<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<div style="padding: 4px;">
    <p style="color:#6b7280; font-size:.82rem; margin-bottom:12px;">Beginilah tampilannya di footer website:</p>

    <div style="background:#0c1023; border-radius:16px; padding:28px; text-align:center;">
        <a href="{{ $link->href }}" target="_blank" rel="noopener"
           style="display:inline-grid; place-items:center; width:52px; height:52px; border-radius:14px;
                  background:rgba(255,255,255,.07); color:#fff; font-size:1.5rem; text-decoration:none;">
            <i class="bi {{ $link->icon }}"></i>
        </a>
        <div style="color:#fff; margin-top:14px; font-weight:700;">{{ $link->platform_label }}</div>
        <div style="color:#60a5fa; font-size:.85rem; word-break:break-all; margin-top:4px;">{{ $link->href }}</div>
    </div>

    <div style="margin-top:14px; display:flex; gap:8px; align-items:flex-start; color:#6b7280; font-size:.85rem;">
        <i class="bi bi-geo-alt" style="color:#4f46e5;"></i>
        <span>Muncul di: <strong style="color:#374151;">footer website, bagian "Ikuti Kami / {{ setting('social_heading', 'Sosial') }}"</strong>
        @unless($link->is_active) <span style="color:#dc2626;">(saat ini NONAKTIF, tidak tampil)</span> @endunless
        </span>
    </div>
</div>
