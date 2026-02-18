<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index()
    {
        // collect distinct types first (avant pagination)
        $types = Pokemon::pluck('type1')
            ->merge(Pokemon::pluck('type2'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $type = request('type');
        $search = request('search');

        $query = Pokemon::orderBy('pokedex_number');

        if ($type) {
            $query->where(function ($q) use ($type) {
                $q->where('type1', $type)->orWhere('type2', $type);
            });
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $pokemons = $query->paginate(30)->appends(request()->query());

        $typeLabels = [
            'normal' => 'Normal',
            'fire' => 'Fire',
            'water' => 'Water',
            'grass' => 'Grass',
            'electric' => 'Electric',
            'ice' => 'Ice',
            'fighting' => 'Fighting',
            'poison' => 'Poison',
            'ground' => 'Ground',
            'flying' => 'Flying',
            'psychic' => 'Psychic',
            'bug' => 'Bug',
            'rock' => 'Rock',
            'ghost' => 'Ghost',
            'dark' => 'Dark',
            'dragon' => 'Dragon',
            'steel' => 'Steel',
            'fairy' => 'Fairy',
        ];

        return view('pokemon.index', compact('pokemons', 'types', 'typeLabels'));
    }

    /**
     * Display the detailed page for a single Pokemon.
     */
    public function show($id)
    {
        $pokemon = Pokemon::findOrFail($id);

        return view('pokemon.show', compact('pokemon'));
    }
}

