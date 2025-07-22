<?php

namespace App\Providers;

use App\Models\BarangMasuk;
use App\Models\Permintaan;
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
        //
        View::composer('*', function ($view) {
        $view->with('jumlahMenunggu', Permintaan::where('status', 'menunggu')->count());
        $view->with('jumlahButuhValidasi', Permintaan::where('status', 'butuh_validasi_manager')->count());
        $view->with('jumlahBelumDiterima', BarangMasuk::whereNull('tanggal_diterima')->count());
    });
    }
}
