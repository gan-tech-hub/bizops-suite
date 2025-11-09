<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Pdf::setOptions([
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'defaultFont' => 'NotoSansJP',
        ]);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
