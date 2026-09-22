<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PoemReader;
use App\Livewire\Settings;

Route::get('/', PoemReader::class)->name('home');

Route::get('configuracoes', Settings::class)
    ->middleware('auth')
    ->name('settings');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
