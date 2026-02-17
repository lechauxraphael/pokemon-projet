<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        $type = request('type');

        $query = Pokemon::orderBy('pokedex_number');

        if ($type) {
            $query->where(function ($q) use ($type) {
                $q->where('type1', $type)->orWhere('type2', $type);
            });
        }

        $pokemons = $query->get();

        // collect distinct types from both columns
        $types = Pokemon::pluck('type1')
            ->merge(Pokemon::pluck('type2'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // French labels for common types (fallback to ucfirst)
        $typeLabels = [
            'normal' => 'Normal',
            'fire' => 'Feu',
            'water' => 'Eau',
            'grass' => 'Plante',
            'electric' => 'Électrique',
            'ice' => 'Glace',
            'fighting' => 'Combat',
            'poison' => 'Poison',
            'ground' => 'Sol',
            'flying' => 'Vol',
            'psychic' => 'Psy',
            'bug' => 'Insecte',
            'rock' => 'Roche',
            'ghost' => 'Spectre',
            'dark' => 'Ténèbres',
            'dragon' => 'Dragon',
            'steel' => 'Acier',
            'fairy' => 'Fée',
        ];

        return view('pokemon.index', compact('pokemons', 'types', 'typeLabels'));
    }
}

