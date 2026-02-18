<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

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
    public function show($pokedex_number)
    {
        $pokemon = Pokemon::where('pokedex_number', $pokedex_number)->firstOrFail();

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

        $inDeck = in_array($pokemon->pokedex_number, session('deck', []));
        return view('pokemon.show', compact('pokemon', 'typeLabels', 'inDeck'));
    }

    /**
     * Display user's deck (session-based).
     */
    public function deck()
    {
        $deck = session('deck', []);
        $pokemons = $deck ? Pokemon::whereIn('pokedex_number', $deck)->orderBy('pokedex_number')->get() : collect();
        return view('pokemon.deck', compact('pokemons'));
    }

    /**
     * Add a Pokemon to the session deck (max 6).
     */
    public function addToDeck(Request $request, $pokedex_number)
    {
        $deck = session('deck', []);
        if (in_array((int)$pokedex_number, $deck)) {
            return redirect()->back()->with('status', 'This Pokémon is already in your deck.');
        }
        if (count($deck) >= 6) {
            return redirect()->back()->with('error', 'Deck is full (maximum 6 Pokémon).');
        }
        $deck[] = (int)$pokedex_number;
        session(['deck' => $deck]);
        return redirect()->back()->with('status', 'Pokémon added to your deck.');
    }

    /**
     * Remove a Pokemon from the session deck.
     */
    public function removeFromDeck(Request $request, $pokedex_number)
    {
        $deck = session('deck', []);
        $deck = array_values(array_filter($deck, function ($n) use ($pokedex_number) {
            return $n != (int)$pokedex_number;
        }));
        session(['deck' => $deck]);
        return redirect()->back()->with('status', 'Pokémon removed from your deck.');
    }
}

