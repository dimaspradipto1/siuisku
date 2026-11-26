<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgamaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BahasaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EselonController;
use App\Http\Controllers\GelarAkademikController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\HubunganKerjaController;
use App\Http\Controllers\JabatanFungsionalController;
use App\Http\Controllers\JabatanStrukturalController;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\JenisHariController;
use App\Http\Controllers\JenisIzinController;
use App\Http\Controllers\JenisJabatanStrukturalController;
use App\Http\Controllers\JenisKehadiranController;
use App\Http\Controllers\JenisKenaikanPangkatController;
use App\Http\Controllers\JenisLuaranController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\JenisPenghargaanController;
use App\Http\Controllers\JenisPkmController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\JenisSkController;
use App\Http\Controllers\JenisTesController;
use App\Http\Controllers\KabupatenKotaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KlasifikasiSdmController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\NegaraController;
use App\Http\Controllers\OutputPenelitianController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\RumpunBidangIlmuController;
use App\Http\Controllers\StatusAktifController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::controller(AuthController::class)->group(function(){
    Route::get('/', 'login')->name('login');
    Route::post('loginproses', 'loginproses')->name('loginproses');
    Route::get('register', 'register')->name('register');
    Route::post('registerproses', 'registerproses')->name('registerproses');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'checkrole'])->group(function(){
    Route::get('modul', [ModulController::class, 'index'])->name('modul');
    Route::get('modul/masuk/{userRole}', [ModulController::class, 'masuk'])->name('modul.masuk');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:modul.view')->prefix('pengaturan/modul')->name('pengaturan.modul.')->controller(ModulController::class)->group(function () {
        Route::get('/', 'pengaturan')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{modul}', 'update')->name('update');
        Route::put('{modul}/users', 'syncUsers')->name('users.sync');
        Route::patch('{modul}/toggle', 'toggleStatus')->name('toggle');
        Route::delete('{modul}', 'destroy')->name('destroy');
    });

    Route::middleware('permission:backup.view')->prefix('pengaturan/backup')->name('pengaturan.backup.')->controller(BackupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('database/export', 'exportDatabase')->name('database.export');
        Route::post('database/import', 'importDatabase')->name('database.import');
        Route::get('storage/export', 'exportStorage')->name('storage.export');
        Route::post('storage/import', 'importStorage')->name('storage.import');
    });

    Route::middleware('permission:users.view')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('permission:referensi.view')->group(function () {
        Route::resource('jenis-jabatan-struktural', JenisJabatanStrukturalController::class)->except(['show']);
        Route::resource('jabatan-struktural', JabatanStrukturalController::class)->except(['show']);
        Route::resource('unit-kerja', UnitKerjaController::class)->except(['show']);
        Route::resource('jenis-sk', JenisSkController::class)->except(['show', 'edit']);
        Route::resource('status-aktif', StatusAktifController::class)->except(['show']);
        Route::resource('hubungan-kerja', HubunganKerjaController::class)->except(['show']);
        Route::resource('pangkat', PangkatController::class)->except(['show']);
        Route::resource('jenis-kenaikan-pangkat', JenisKenaikanPangkatController::class)->except(['show']);
        Route::resource('klasifikasi-sdm', KlasifikasiSdmController::class)->except(['show']);
        Route::resource('jabatan-fungsional', JabatanFungsionalController::class)->except(['show']);
        Route::resource('eselon', EselonController::class)->except(['show']);
        Route::resource('rumpun-bidang-ilmu', RumpunBidangIlmuController::class)->except(['show']);
        Route::resource('gelar-akademik', GelarAkademikController::class)->except(['show']);
        Route::resource('jenis-sertifikasi', JenisSertifikasiController::class)->except(['show']);
        Route::resource('jenis-tes', JenisTesController::class)->parameters(['jenis-tes' => 'jenisTes'])->except(['show']);
        Route::resource('jenis-pkm', JenisPkmController::class)->except(['show']);
        Route::resource('output-penelitian', OutputPenelitianController::class)->except(['show']);
        Route::resource('jenis-penghargaan', JenisPenghargaanController::class)->except(['show']);
        Route::resource('jenis-pelanggaran', JenisPelanggaranController::class)->except(['show']);
        Route::resource('jenis-luaran', JenisLuaranController::class)->except(['show']);
        Route::resource('negara', NegaraController::class)->except(['show']);
        Route::resource('provinsi', ProvinsiController::class)->except(['show']);
        Route::resource('kabupaten-kota', KabupatenKotaController::class)
            ->parameters(['kabupaten-kota' => 'kabupatenKota'])
            ->except(['show']);
        Route::resource('kecamatan', KecamatanController::class)->except(['show']);
        Route::resource('jenis-kehadiran', JenisKehadiranController::class)->except(['show']);
        Route::resource('jenis-cuti', JenisCutiController::class)->except(['show']);
        Route::resource('jenis-izin', JenisIzinController::class)->except(['show']);
        Route::resource('jenis-hari', JenisHariController::class)->only(['index', 'update']);
        Route::resource('hari-libur', HariLiburController::class)->except(['show']);
        Route::resource('jam-kerja', JamKerjaController::class)->except(['show']);
        Route::resource('agama', AgamaController::class)->except(['show']);
        Route::resource('bahasa', BahasaController::class)->except(['show']);
    });

    Route::middleware('permission:hak_akses.view')->group(function () {
        Route::resource('hak-akses', HakAksesController::class)->except(['show']);
        Route::post('hak-akses/plotting', [HakAksesController::class, 'storePlotting'])->name('hak-akses.plotting.store');
        Route::delete('hak-akses/plotting/{userRole}', [HakAksesController::class, 'destroyPlotting'])->name('hak-akses.plotting.destroy');
    });

    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
        Route::put('password', 'updatePassword')->name('password');
        Route::delete('photo', 'deletePhoto')->name('photo.destroy');
    });

    Route::prefix('activity')->name('activity.')->controller(ActivityLogController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('export', 'export')->name('export');
    });
});