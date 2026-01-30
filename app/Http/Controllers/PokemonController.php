<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        $pokemons = Pokemon::orderBy('pokedex_number')->get();
        return view('pokemon.index', compact('pokemons'));
    }
}

