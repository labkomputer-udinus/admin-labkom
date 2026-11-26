<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangTertinggalController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\EksporController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Laporan Permasalahan
    Route::resource('laporan', LaporanController::class);
    Route::post('laporan/{laporan}/items', [LaporanController::class, 'storeItem'])->name('laporan.item.store');
    Route::put('laporan/{laporan}/items/{item}', [LaporanController::class, 'updateItem'])->name('laporan.item.update');
    Route::delete('laporan/{laporan}/items/{item}', [LaporanController::class, 'destroyItem'])->name('laporan.item.destroy');
    Route::patch('laporan/{laporan}/items/{item}/konfirmasi-selesai', [LaporanController::class, 'konfirmasiSelesaiItem'])->name('laporan.item.konfirmasi-selesai');
    Route::post('laporan/{laporan}/items/{item}/penanganan', [LaporanController::class, 'storePenanganan'])->name('laporan.item.penanganan.store');
    Route::put('laporan/{laporan}/items/{item}/penanganan/{penanganan}', [LaporanController::class, 'updatePenanganan'])->name('laporan.item.penanganan.update');
    Route::delete('laporan/{laporan}/items/{item}/penanganan/{penanganan}', [LaporanController::class, 'destroyPenanganan'])->name('laporan.item.penanganan.destroy');

    // Pengajuan Pengadaan
    Route::resource('pengajuan', PengajuanController::class);
    Route::post('pengajuan/{pengajuan}/items', [PengajuanController::class, 'storeItem'])->name('pengajuan.item.store');
    Route::put('pengajuan/{pengajuan}/items/{item}', [PengajuanController::class, 'updateItem'])->name('pengajuan.item.update');
    Route::delete('pengajuan/{pengajuan}/items/{item}', [PengajuanController::class, 'destroyItem'])->name('pengajuan.item.destroy');
    Route::post('pengajuan/{pengajuan}/keputusan', [PengajuanController::class, 'updateKeputusan'])->name('pengajuan.keputusan');

    // Verifikasi Penerimaan Barang
    Route::get('penerimaan/{pengajuan}', [PenerimaanController::class, 'show'])->name('penerimaan.show');
    Route::post('penerimaan/{pengajuan}/simpan', [PenerimaanController::class, 'simpan'])->name('penerimaan.simpan');

    // Gudang & Inventaris
    Route::get('gudang', [GudangController::class, 'index'])->name('gudang.index');
    Route::get('gudang/search-barang', [GudangController::class, 'searchBarang'])->name('gudang.search-barang');
    Route::post('gudang', [GudangController::class, 'store'])->name('gudang.store');
    Route::get('gudang/{stok}', [GudangController::class, 'show'])->name('gudang.show');
    Route::put('gudang/{stok}', [GudangController::class, 'update'])->name('gudang.update');
    Route::delete('gudang/{stok}', [GudangController::class, 'destroy'])->name('gudang.destroy');
    Route::get('gudang/{stok}/mutasi', [GudangController::class, 'show'])->name('gudang.mutasi');
    Route::post('gudang/{stok}/penyesuaian', [GudangController::class, 'sesuaikanStok'])->name('gudang.penyesuaian');

    // Distribusi
    Route::resource('distribusi', DistribusiController::class)->only(['index', 'create', 'store']);
    Route::patch('distribusi/{distribusi}/konfirmasi-terima', [DistribusiController::class, 'konfirmasiTerima'])->name('distribusi.konfirmasi-terima');
    Route::delete('distribusi/{distribusi}', [DistribusiController::class, 'destroy'])->name('distribusi.destroy');

    // Barang Tertinggal (Lost & Found)
    Route::resource('barang-tertinggal', BarangTertinggalController::class);
    Route::patch('barang-tertinggal/{barang_tertinggal}/status', [BarangTertinggalController::class, 'updateStatus'])->name('barang-tertinggal.status');

    // Ekspor Data
    Route::prefix('ekspor')->name('ekspor.')->group(function () {
        Route::get('laporan/excel', [EksporController::class, 'laporanExcel'])->name('laporan.excel');
        Route::get('laporan/pdf', [EksporController::class, 'laporanPdf'])->name('laporan.pdf');
        Route::get('laporan/{laporan}/excel', [EksporController::class, 'riwayatLaporanExcel'])->name('laporan.detail.excel');
        Route::get('laporan/{laporan}/pdf', [EksporController::class, 'riwayatLaporanPdf'])->name('laporan.detail.pdf');
        Route::get('stok/excel', [EksporController::class, 'stokExcel'])->name('stok.excel');
        Route::get('stok/pdf', [EksporController::class, 'stokPdf'])->name('stok.pdf');
        Route::get('distribusi/excel', [EksporController::class, 'distribusiExcel'])->name('distribusi.excel');
        Route::get('distribusi/pdf', [EksporController::class, 'distribusiPdf'])->name('distribusi.pdf');
        Route::get('pengajuan/excel', [EksporController::class, 'pengajuanExcel'])->name('pengajuan.excel');
        Route::get('pengajuan/pdf', [EksporController::class, 'pengajuanPdf'])->name('pengajuan.pdf');
        Route::get('pengajuan/{pengajuan}/excel', [EksporController::class, 'detailPengajuanExcel'])->name('pengajuan.detail.excel');
        Route::get('pengajuan/{pengajuan}/pdf', [EksporController::class, 'detailPengajuanPdf'])->name('pengajuan.detail.pdf');
        Route::get('barang-tertinggal/excel', [EksporController::class, 'barangTertinggalExcel'])->name('barang-tertinggal.excel');
        Route::get('barang-tertinggal/pdf', [EksporController::class, 'barangTertinggalPdf'])->name('barang-tertinggal.pdf');
    });
});
