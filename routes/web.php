<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Demo\DemoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\PermintaanController;
use App\Http\Controllers\Pos\KelompokController;
use App\Http\Controllers\Pos\BarangController;
use App\Http\Controllers\Pos\PilihanController;
use App\Http\Controllers\Pos\DefaultController;
use App\Http\Controllers\WizardController;

Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
Route::post('/wizard-submit', [WizardController::class, 'submit'])->name('wizard.submit');


Route::get('/', function () {
    return view('welcome');
});


Route::controller(DemoController::class)->group(function () {
    Route::get('/about', 'Index')->name('about.page')->middleware('check');
    Route::get('/contact', 'ContactMethod')->name('cotact.page');
});


 // Admin All Route 
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'Profile')->name('admin.profile');
    Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
    Route::post('/store/profile', 'StoreProfile')->name('store.profile');

    Route::get('/change/password', 'ChangePassword')->name('change.password');
    Route::post('/update/password', 'UpdatePassword')->name('update.password');
     
});


  // Admin All Route 
Route::controller(PermintaanController::class)->group(function () {
    Route::get('/permintaan/all', 'PermintaanAll')->name('permintaan.all');

     
});


Route::controller(KelompokController::class)->group(function () {
    Route::get('/kelompok/all', 'KelompokAll')->name('kelompok.all');
    Route::get('/kelompok/add', 'KelompokAdd')->name('kelompok.add');
    Route::post('/kelompok/store', 'KelompokStore')->name('kelompok.store');
    Route::get('/kelompok/edit/{id}', 'KelompokEdit')->name('kelompok.edit');
    Route::post('/kelompok/update/{id}', 'KelompokUpdate')->name('kelompok.update'); 
    Route::get('/kelompok/delete/{id}', 'KelompokDelete')->name('kelompok.delete');
});

Route::controller(BarangController::class)->group(function () {
    Route::get('/barang/all', 'BarangAll')->name('barang.all');
    Route::get('/barang/add', 'BarangAdd')->name('barang.add');
    Route::post('/barang/store', 'BarangStore')->name('barang.store');
    Route::get('/barang/edit/{id}', 'BarangEdit')->name('barang.edit');
    Route::post('/barang/update/{id}', 'BarangUpdate')->name('barang.update'); 
    Route::get('/barang/delete/{id}', 'BarangDelete')->name('barang.delete'); 
});

Route::controller(PilihanController::class)->group(function () {
    Route::get('/pilihan/all', 'PilihanAll')->name('pilihan.all');
    Route::get('/pilihan/add', 'PilihanAdd')->name('pilihan.add');
    // Route::post('/pilihan/store', 'PilihanStore')->name('pilihan.store');
    // Route::get('/pilihan/edit/{id}', 'PilihanEdit')->name('pilihan.edit');
    // Route::post('/pilihan/update/{id}', 'PilihanUpdate')->name('pilihan.update'); 
    // Route::get('/pilihan/delete/{id}', 'PilihanDelete')->name('pilihan.delete'); 
});

Route::controller(DefaultController::class)->group(function () {
    Route::get('/get-category', 'GetCategory')->name('get-category');
    // Route::get('/pilihan/add', 'PilihanAdd')->name('pilihan.add');
    // Route::post('/pilihan/store', 'PilihanStore')->name('pilihan.store');
    // Route::get('/pilihan/edit/{id}', 'PilihanEdit')->name('pilihan.edit');
    // Route::post('/pilihan/update/{id}', 'PilihanUpdate')->name('pilihan.update'); 
    // Route::get('/pilihan/delete/{id}', 'PilihanDelete')->name('pilihan.delete'); 
});





Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


// Route::get('/contact', function () {
//     return view('contact');
// });
