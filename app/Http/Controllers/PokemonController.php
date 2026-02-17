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

        // paginate 20 par page et appends les param de requête (type=...)
        $pokemons = $query->paginate(30)->appends(request()->query());

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

