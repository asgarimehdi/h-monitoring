<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/password');


    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('kargozini/estekhdam', 'kargozini.Estekhdam')->name('kargozini.Estekhdam');
});

require __DIR__ . '/auth.php';

Volt::route('units/create', 'create-units')->name('units.create');
Volt::route('units/chart', 'chart-units')->name('units.chart');