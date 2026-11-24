<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModulController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pengaturan/modul')->name('pengaturan.modul.')->controller(ModulController::class)->group(function () {
        Route::get('/', 'pengaturan')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{modul}', 'update')->name('update');
        Route::patch('{modul}/toggle', 'toggleStatus')->name('toggle');
        Route::delete('{modul}', 'destroy')->name('destroy');
    });

    Route::resource('users', UserController::class);
});