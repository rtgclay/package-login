<?php

use Illuminate\Support\Facades\Route;
use Smpl\Login\Http\Controllers\LoginController;


Route::get('/', function () {
    return view('login::index');
})->name('login');

Route::get('/test', function () {
    return view('test::index');
})->name('test');
