<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Livewire\ChatBot;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/chatbot', ChatBot::class);
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
