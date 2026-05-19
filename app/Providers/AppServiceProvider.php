<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id');
        Paginator::defaultView('vendor.pagination.siplas');
        Paginator::defaultSimpleView('vendor.pagination.siplas-simple');

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
