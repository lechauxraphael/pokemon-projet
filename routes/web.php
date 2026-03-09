<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/pokemon', [App\Http\Controllers\PokemonController::class, 'index'])->name('pokemon');
    Route::get('/pokemon/{pokedex_number}', [App\Http\Controllers\PokemonController::class, 'show'])->name('pokemon.show');
    Route::get('/deck', [App\Http\Controllers\PokemonController::class, 'deck'])->name('deck');
    Route::post('/deck/add/{pokedex_number}', [App\Http\Controllers\PokemonController::class, 'addToDeck'])->name('deck.add');
    Route::post('/deck/remove/{pokedex_number}', [App\Http\Controllers\PokemonController::class, 'removeFromDeck'])->name('deck.remove');
    Route::post('/deck/store', [App\Http\Controllers\PokemonController::class, 'storeDeck'])->name('deck.store');
    Route::post('/deck/{deck}/rename', [App\Http\Controllers\PokemonController::class, 'renameDeck'])->name('deck.rename');
    Route::post('/deck/{deck}/delete', [App\Http\Controllers\PokemonController::class, 'destroyDeck'])->name('deck.delete');
    Route::post('/deck/add-pokemon', [App\Http\Controllers\PokemonController::class, 'addPokemonToSavedDeck'])->name('deck.add_pokemon');
    Route::post('/deck/{deck}/remove-pokemon', [App\Http\Controllers\PokemonController::class, 'removePokemonFromSavedDeck'])->name('deck.remove_pokemon');
    
    Route::get('/home', function () {
        return redirect()->route('pokemon');
    })->name('home');
});
