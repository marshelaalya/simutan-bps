<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Demo\DemoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pos\UserController;
use App\Http\Controllers\Pos\PermintaanController;
use App\Http\Controllers\Pos\KelompokController;
use App\Http\Controllers\Pos\BarangController;
use App\Http\Controllers\Pos\PilihanController;
use App\Http\Controllers\Pos\DefaultController;
use App\Http\Controllers\WizardController;
use App\Models\Permintaan;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Auth\LoginController;


// Halaman login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rute umum yang tidak memerlukan otentikasi
Route::get('/', function () {
    return view('auth/login');
});

Route::get('/index', function () {
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $totalPermintaanBulanIni = Permintaan::whereBetween('tgl_request', [$startOfMonth, $endOfMonth])->count();
    return view('index', ['totalPermintaanBulanIni' => $totalPermintaanBulanIni]);
});

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tambahkan di dalam grup middleware auth

    // Rute untuk menandai semua notifikasi sebagai dibaca
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllRead'])->name('notifications.markAllRead');
    

    // Rute untuk melihat semua notifikasi (opsional)
    Route::get('/notifications', [DashboardController::class, 'viewAllNotifications'])->name('notifications.viewAll');
    
    
    Route::get('/admin/index', [DashboardController::class, 'index'])->name('admin.index');
    Route::get('/pegawai/index', [DashboardController::class, 'index'])->name('pegawai.index');

    

    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'Profile')->name('admin.profile');
        Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
        Route::post('/store/profile', 'StoreProfile')->name('store.profile');
        Route::get('/change/password', 'ChangePassword')->name('change.password');
        Route::post('/update/password', 'UpdatePassword')->name('update.password');
    });

    Route::controller(PegawaiController::class)->group(function () {
        Route::get('/pegawai/logout', 'destroy')->name('pegawai.logout');
        Route::get('/pegawai/profile', 'Profile')->name('pegawai.profile');
        Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
        Route::post('/store/profile', 'StoreProfile')->name('store.profile');
        Route::get('/change/password', 'ChangePassword')->name('change.password');
        Route::post('/update/password', 'UpdatePassword')->name('update.password');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/all', 'UserAll')->name('user.all');
        Route::get('/user/add', 'UserAdd')->name('user.add');
        Route::post('/user/store', 'UserStore')->name('user.store');
        Route::get('/user/edit/{id}', 'UserEdit')->name('user.edit');
        Route::put('/user/update/{id}', 'UserUpdate')->name('user.update');
        Route::get('/user/delete/{id}', 'UserDelete')->name('user.delete');
    });

    Route::controller(PermintaanController::class)->group(function () {
        Route::get('/permintaan/all', 'PermintaanAll')->name('permintaan.all');
        Route::get('/permintaan/add', 'PermintaanAdd')->name('permintaan.add');
        Route::get('/permintaan/view/{id}', 'PermintaanView')->name('permintaan.view');
        Route::get('permintaan/approve/{id}', 'PermintaanApprove')->name('permintaan.approve');
        Route::patch('permintaan/update-status/{id}', 'PermintaanUpdateStatus')->name('permintaan.updateStatus');
        Route::get('/permintaan/saya', 'PermintaanSaya')->name('permintaan.saya');
        Route::get('/permintaan/{id}/edit', 'PermintaanEdit')->name('permintaan.edit');
        Route::put('/permintaan/update/{id}', 'PermintaanUpdate')->name('permintaan.update');
        Route::get('/permintaan/delete/{id}', 'PermintaanDelete')->name('permintaan.delete');
        Route::get('/permintaan/print/{id}', 'PermintaanPrint')->name('permintaan.print');
        
        Route::get('permintaan/data', [PermintaanController::class, 'getPermintaanData'])->name('permintaan.data');
        Route::resource('permintaan', PermintaanController::class);
    });

    Route::controller(KelompokController::class)->group(function () {
        Route::get('/kelompok/all', 'KelompokAll')->name('kelompok.all');
        Route::get('/kelompok/add', 'KelompokAdd')->name('kelompok.add');
        Route::post('/kelompok/store', 'KelompokStore')->name('kelompok.store');
        Route::get('/kelompok/edit/{id}', 'KelompokEdit')->name('kelompok.edit');
        Route::put('/kelompok/update/{id}', 'KelompokUpdate')->name('kelompok.update');
        Route::get('/kelompok/delete/{id}', 'KelompokDelete')->name('kelompok.delete');
        Route::get('kelompok/data',  'data')->name('kelompok.data');

    });

    Route::controller(BarangController::class)->group(function () {
        Route::get('/barang/all', 'BarangAll')->name('barang.all');
        Route::get('/barang/all-act', 'BarangAllAct')->name('barang.allAct');
        Route::get('/barang/add', 'BarangAdd')->name('barang.add');
        Route::post('/barang/store', 'BarangStore')->name('barang.store');
        Route::get('/barang/edit/{id}', 'BarangEdit')->name('barang.edit');
        Route::post('/barang/update/{id}', 'BarangUpdate')->name('barang.update');
        Route::get('/barang/delete/{id}', 'BarangDelete')->name('barang.delete');
        Route::get('barang/data-all', 'dataForAll')->name('barang.data.all');
        Route::get('barang/data-index', 'dataForIndex')->name('barang.data.index');    

                Route::post('/barang/add-stock', 'addStock')->name('barang.addStock');
        // web.php

    });

    Route::controller(PilihanController::class)->group(function () {
        Route::get('/pilihan/all', 'PilihanAll')->name('pilihan.all');
        Route::get('/pilihan/add', 'PilihanAdd')->name('pilihan.add');
        Route::post('/pilihan/store', 'PilihanStore')->name('pilihan.store');
        Route::get('/pilihan/edit/{id}', 'PilihanEdit')->name('pilihan.edit');
        Route::put('/pilihan/update/{id}', 'PilihanUpdate')->name('pilihan.update');
        Route::get('/pilihan/admin-approval', 'PilihanAdminAppr')->name('pilihan.admAppr');
        Route::get('/pilihan/delete/{id}', 'PilihanDelete')->name('pilihan.delete');
    });

    Route::controller(DefaultController::class)->group(function () {
        Route::get('/get-category', 'GetCategory')->name('get-category');
        Route::get('/get-satuan', 'GetSatuan')->name('get-satuan');
    });
});

Route::controller(DemoController::class)->group(function () {
    Route::get('/about', 'Index')->name('about.page')->middleware('check');
    Route::get('/contact', 'ContactMethod')->name('contact.page');
});

Route::controller(WizardController::class)->group(function () {
    Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
    Route::post('/wizard-submit', [WizardController::class, 'submit'])->name('wizard.submit');
});

require __DIR__.'/auth.php';
