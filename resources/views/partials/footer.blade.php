@php $siteName = setting('site_name', config('app.name', 'RPD')); @endphp
<footer class="site-footer pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="foot-brand h4 mb-2">{{ $siteName }}</div>
                <p class="text-secondary mb-3" style="max-width: 320px;">{{ setting('site_tagline', 'Produk berkualitas dengan garansi resmi.') }}</p>
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

            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Navigasi</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                    <li><a href="{{ route('leaderboard.index') }}">Leaderboard</a></li>
                    <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Layanan</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('invoice.index') }}">Cek Invoice</a></li>
                    <li><a href="{{ route('warranty.index') }}">Klaim Garansi</a></li>
                    <li><a href="{{ route('contact.index') }}">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="foot-brand mb-3">Ikuti Kami</h6>
                <div class="d-flex gap-2">
                    @if ($fb = setting('facebook_url'))
                        <a class="soc" href="{{ $fb }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if ($ig = setting('instagram_url'))
                        <a class="soc" href="{{ $ig }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if ($tt = setting('tiktok_url'))
                        <a class="soc" href="{{ $tt }}" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if ($yt = setting('youtube_url'))
                        <a class="soc" href="{{ $yt }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if ($wa = setting('company_whatsapp'))
                        <a class="soc" href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-4" style="border-color: rgba(255,255,255,.08);">

        <p class="text-center text-secondary small mb-0">
            {{ setting('footer_text', '© '.date('Y').' '.$siteName.'. All rights reserved.') }}
        </p>
    </div>
</footer>
