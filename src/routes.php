<?php
use Illuminate\Support\Facades\Route;
use Smpl\Login\Http\Controllers\LoginController;


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::middleware(['guest', 'web'])->group(function () {
	// Route::get('/login', function () {
    //     return view('login::index');
    // })->name('login');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});