<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/pokemon', [App\Http\Controllers\PokemonController::class, 'index'])->name('pokemon');
    Route::get('/pokemon/{pokedex_number}', [App\Http\Controllers\PokemonController::class, 'show'])->name('pokemon.show');
    
    Route::get('/home', function () {
        return redirect()->route('pokemon');
    })->name('home');
});

