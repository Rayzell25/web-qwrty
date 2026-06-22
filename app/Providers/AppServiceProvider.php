<?php

namespace App\Providers;

use App\Services\Otp\OtpService;
use App\Services\WhatsApp\LogWhatsAppProvider;
use App\Services\WhatsApp\HttpWhatsAppProvider;
use App\Services\WhatsApp\WhatsAppProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the WhatsApp provider abstraction based on configuration.
        $this->app->singleton(WhatsAppProvider::class, function ($app) {
            $provider = config('services.whatsapp.provider', 'log');

            return match ($provider) {
                'http', 'api' => new HttpWhatsAppProvider(),
                default => new LogWhatsAppProvider(),
            };
        });

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app->make(WhatsAppProvider::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Avoid index length issues on older database engines.
        Schema::defaultStringLength(191);

        // Paksa HTTPS pada semua URL jika APP_URL https (mis. di belakang
        // Cloudflare Flexible). Mencegah aset/JS dimuat via http -> diblokir.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
