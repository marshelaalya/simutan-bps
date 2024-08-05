<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Permintaan;

/**
 * Bootstrap any application services.
 *
 * @return void
 */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        View::composer('*', function ($view) {
            // Mendapatkan tanggal awal dan akhir bulan ini
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Menghitung total permintaan bulan ini
            $totalPermintaanBulanIni = Permintaan::whereBetween('tgl_request', [$startOfMonth, $endOfMonth])->count();

            // Menghitung total permintaan selesai (status = 'accepted')
            $totalPermintaanSelesai = Permintaan::whereBetween('tgl_request', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved by supervisor')
                ->count();

            // Menghitung total permintaan pending (status = 'pending' atau 'approved by admin')
            $totalPermintaanPending = Permintaan::whereBetween('tgl_request', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['pending', 'approved by admin'])
                ->count();
            
            $totalPermintaanRejected = Permintaan::whereBetween('tgl_request', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['rejected by admin', 'rejected by supervisor'])
                ->count();

            // Menyuntikkan data ke view
            $view->with([
                'totalPermintaanBulanIni' => $totalPermintaanBulanIni,
                'totalPermintaanSelesai' => $totalPermintaanSelesai,
                'totalPermintaanPending' => $totalPermintaanPending,
                'totalPermintaanRejected' => $totalPermintaanRejected,
            ]);
        });
    }
}
