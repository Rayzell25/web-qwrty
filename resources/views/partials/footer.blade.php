<footer class="bg-dark text-light pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold">{{ setting('site_name', config('app.name', 'RPD')) }}</h5>
                <p class="text-secondary mb-2">{{ setting('site_tagline') }}</p>
                @if ($address = setting('company_address'))
                    <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $address }}</p>
                @endif
                @if ($phone = setting('company_phone'))
                    <p class="mb-1"><i class="bi bi-telephone"></i> {{ $phone }}</p>
                @endif
                @if ($email = setting('company_email'))
                    <p class="mb-1"><i class="bi bi-envelope"></i> {{ $email }}</p>
                @endif
            </div>

            <div class="col-md-4">
                <h6 class="fw-bold">Tautan</h6>
                <ul class="list-unstyled">
                    <li><a class="link-light" href="{{ route('products.index') }}">Produk</a></li>
                    <li><a class="link-light" href="{{ route('leaderboard.index') }}">Leaderboard</a></li>
                    <li><a class="link-light" href="{{ route('faq.index') }}">FAQ</a></li>
                    <li><a class="link-light" href="{{ route('invoice.index') }}">Cek Invoice</a></li>
                    <li><a class="link-light" href="{{ route('warranty.index') }}">Klaim Garansi</a></li>
                    <li><a class="link-light" href="{{ route('contact.index') }}">Kontak</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h6 class="fw-bold">Ikuti Kami</h6>
                <div class="d-flex gap-3 fs-4">
                    @if ($fb = setting('facebook_url'))
                        <a class="link-light" href="{{ $fb }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if ($ig = setting('instagram_url'))
                        <a class="link-light" href="{{ $ig }}" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if ($tt = setting('tiktok_url'))
                        <a class="link-light" href="{{ $tt }}" target="_blank" rel="noopener"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if ($yt = setting('youtube_url'))
                        <a class="link-light" href="{{ $yt }}" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if ($wa = setting('company_whatsapp'))
                        <a class="link-light" href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <p class="text-center text-secondary mb-0">
            {{ setting('footer_text', '© '.date('Y').' '.setting('site_name', config('app.name', 'RPD')).'. All rights reserved.') }}
        </p>
    </div>
</footer>
