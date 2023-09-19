<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


// Authentication Routes (Login, Logout)
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginCheck')->name('login.check');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('pages.dashboard.dashboard');
    })->name('dashboard');
});

// Mahasiswa Routes (Tugas, Materi, Virtual Lab, Fitur)




Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
