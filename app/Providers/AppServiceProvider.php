<?php

namespace App\Providers;

use App\View\Composers\SiteSettingComposer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Model::preventLazyLoading(! app()->isProduction());

        // force https
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        View::composer(
            ['components.navbar', 'components.footer', 'components.layouts.public', 'livewire.pages.*'],
            SiteSettingComposer::class,
        );
    }
}
