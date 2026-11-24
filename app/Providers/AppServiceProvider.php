<?php

namespace App\Providers;

use App\Models\Modul;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
        Carbon::setLocale('id');

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.dashboard.header', function ($view): void {
            $headerModules = Schema::hasTable('moduls')
                ? Modul::query()->orderBy('urutan')->orderBy('id')->get()
                : collect();

            $view->with('headerModules', $headerModules);
        });
    }
}
