<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DasborController;
use App\Http\Controllers\Admin\KontenController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\SitusController;
use Illuminate\Support\Facades\Route;

// Situs publik
Route::controller(SitusController::class)->group(function () {
    Route::get('/', 'beranda')->name('beranda');
    Route::get('/profil', 'profil')->name('profil');
    Route::get('/karya', 'karya')->name('karya');
    Route::get('/karya/{slug}', 'karyaDetail')->name('karya.detail');
    Route::get('/bahan-ajar', 'bahanAjar')->name('bahan-ajar');
    Route::get('/prestasi', 'prestasi')->name('prestasi');
    Route::get('/galeri', 'galeri')->name('galeri');
    Route::get('/artikel', 'artikel')->name('artikel');
    Route::get('/artikel/{slug}', 'artikelDetail')->name('artikel.detail');
    Route::get('/kontak', 'kontak')->name('kontak');
    Route::post('/kontak', 'kirimPesan')->name('kontak.kirim');
    Route::get('/unduh/{jenis}/{id}', 'unduh')->whereIn('jenis', ['karya', 'bahan-ajar'])->whereNumber('id')->name('unduh');
    Route::get('/cv', 'cv')->name('cv');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/robots.txt', 'robots');
});

// Panel admin
Route::get('/admin/masuk', [AuthController::class, 'form'])->name('login');
Route::post('/admin/masuk', [AuthController::class, 'masuk'])->name('login.proses');
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'keluar'])->name('logout');
    Route::get('/', [DasborController::class, 'index'])->name('admin.dasbor');
    Route::get('/profil', [ProfilController::class, 'index'])->name('admin.profil');
    Route::post('/profil', [ProfilController::class, 'simpan'])->name('admin.profil.simpan');
    Route::post('/password', [ProfilController::class, 'password'])->name('admin.password');
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}', [PesanController::class, 'show'])->whereNumber('id')->name('pesan.show');
    Route::delete('/pesan/{id}', [PesanController::class, 'destroy'])->whereNumber('id')->name('pesan.destroy');
    Route::post('/galeri/massal', [KontenController::class, 'galeriMassal'])->name('galeri.massal');
    Route::post('/pratinjau', [KontenController::class, 'pratinjau'])->name('pratinjau');
    Route::controller(KontenController::class)->prefix('{jenis}')->where(['jenis' => 'karya|bahan-ajar|artikel|prestasi|galeri|pendidikan|pengalaman|sertifikat|keahlian|testimoni'])->group(function () {
        Route::get('/', 'index')->name('konten.index');
        Route::get('/baru', 'create')->name('konten.create');
        Route::post('/', 'store')->name('konten.store');
        Route::get('/{id}/ubah', 'edit')->whereNumber('id')->name('konten.edit');
        Route::put('/{id}', 'update')->whereNumber('id')->name('konten.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('konten.destroy');
        Route::post('/{id}/tampil', 'toggle')->whereNumber('id')->name('konten.toggle');
    });
});
