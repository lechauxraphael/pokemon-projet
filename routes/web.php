<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/pokemon', [App\Http\Controllers\PokemonController::class, 'index'])->middleware('auth')->name('pokemon');
Route::get('/home', function () {
    return redirect()->route('pokemon');
})->middleware(['auth', 'verified'])->name('home');

