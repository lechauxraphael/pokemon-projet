<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/pokemon', [App\Http\Controllers\PokemonController::class, 'index'])->middleware('auth')->name('pokemon');
Route::get('/pokemon/{id}', [App\Http\Controllers\PokemonController::class, 'show'])->middleware('auth')->name('pokemon.show');
Route::get('/home', function () {
    return redirect()->route('pokemon');
})->middleware(['auth', 'verified'])->name('home');

