<?php

use Illuminate\Support\Facades\Route;

// Pantalla de registro
Route::get('/register', function () {
    return view('register');
})->name('register');

// Pantalla de login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Pantalla principal (dashboard)
Route::get('/index', function () {
    return view('index');
})->name('index');
