<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MicrosoftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('dashboard', [HomeController::class, 'calendar'])->name('dashboard');
Route::get('/index', [HomeController::class, 'index']);


Route::resource('gcalendar', GoogleController::class);
Route::get('/oauth', [GoogleController::class, 'oauth'])->name('oauth');
Route::get('/oauth/callback', [GoogleController::class, 'oauthCallback'])->name('oauthCallback');


require __DIR__.'/auth.php';
