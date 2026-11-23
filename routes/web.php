<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});