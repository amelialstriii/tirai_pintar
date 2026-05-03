<?php

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',[Authcontroller::class,'login'])->name('login');
    Route::post('/login/proses', [Authcontroller::class,'proses'])->name('login.proses');
});


Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

