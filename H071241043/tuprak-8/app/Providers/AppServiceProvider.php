<?php

namespace App\Providers;

// 1. TAMBAHKAN BARIS INI
use Illuminate\Pagination\Paginator; // <--- PASTIKAN INI ADA
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
        // Pastikan baris ini ada di dalam 'boot', bukan 'register'
        Paginator::useBootstrapFive();
    }
}