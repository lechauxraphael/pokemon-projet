<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $pokemon->loadCount('decks');
        $decks = Deck::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        foreach ($decks as $d) {
            $d->items_count = (int) DB::table('deck_pokemon')->where('deck_id', $d->id)->sum('quantity');
        }
        $inDeckTotal = (int) DB::table('deck_pokemon')->where('pokemon_id', $pokemon->id)->sum('quantity');

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

        return view('pokemon.show', compact('pokemon', 'typeLabels', 'decks', 'inDeckTotal'));
    }

    /**
     * Display user's deck (session-based).
     */
    public function deck()
    {
        $deck = session('deck', []);
        $pokemons = $deck ? Pokemon::whereIn('pokedex_number', $deck)->orderBy('pokedex_number')->get() : collect();
        $decks = Deck::where('user_id', Auth::id())
            ->with(['pokemons' => function ($q) {
                $q->orderBy('pokedex_number');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($decks as $d) {
            $d->items_count = (int) DB::table('deck_pokemon')->where('deck_id', $d->id)->sum('quantity');
        }
        return view('pokemon.deck', compact('pokemons', 'decks'));
    }

    /**
     * Add a Pokemon to the session deck (max 6).
     */
    public function addToDeck(Request $request, $pokedex_number)
    {
        $deck = session('deck', []);
            /**
     * La fonction commentée servait à vérifier si le pokémon était déjà dans le deck
     */
        // if (in_array((int)$pokedex_number, $deck)) {
        //     return redirect()->back()->with('status', 'This Pokémon is already in your deck.');
        // }
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

    public function storeDeck(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $deck = Deck::create([
            'name' => $data['name'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('status', 'Deck created.');
    }

    public function renameDeck(Request $request, Deck $deck)
    {
        if ($deck->user_id !== Auth::id()) {
            abort(403);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $deck->update(['name' => $data['name']]);
        return redirect()->back()->with('status', 'Deck name updated.');
    }

    public function destroyDeck(Deck $deck)
    {
        if ($deck->user_id !== Auth::id()) {
            abort(403);
        }
        $deck->delete();
        return redirect()->back()->with('status', 'Deck deleted.');
    }

    public function destroyPokemon(Pokemon $pokemon)
    {
        $pokemon->delete();
        return redirect()->route('pokemon')->with('success', $pokemon->name . ' has been deleted.');
    }

    public function addPokemonToSavedDeck(Request $request)
    {
        $data = $request->validate([
            'deck_id' => 'required|integer|exists:decks,id',
            'pokedex_number' => 'required|integer',
        ]);

        $deck = Deck::findOrFail($data['deck_id']);
        if ($deck->user_id !== Auth::id()) {
            abort(403);
        }
        $pokemon = Pokemon::where('pokedex_number', $data['pokedex_number'])->firstOrFail();
        $currentTotal = (int) DB::table('deck_pokemon')->where('deck_id', $deck->id)->sum('quantity');
        if ($currentTotal >= 6) {
            return redirect()->back()->with('error', 'Deck is full (maximum 6 Pokémon).');
        }
        $pivot = DB::table('deck_pokemon')
            ->where('deck_id', $deck->id)
            ->where('pokemon_id', $pokemon->id)
            ->first();
        if ($pivot) {
            DB::table('deck_pokemon')
                ->where('id', $pivot->id)
                ->update(['quantity' => $pivot->quantity + 1, 'updated_at' => now()]);
        } else {
            $deck->pokemons()->attach($pokemon->id, ['quantity' => 1]);
        }

        return redirect()->back()->with('status', 'Pokémon added to deck.');
    }

    public function removePokemonFromSavedDeck(Request $request, Deck $deck)
    {
        if ($deck->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'pokemon_id' => 'required|integer',
        ]);
        $pokemonId = (int) $request->input('pokemon_id');
        $pivot = DB::table('deck_pokemon')
            ->where('deck_id', $deck->id)
            ->where('pokemon_id', $pokemonId)
            ->first();
        if ($pivot) {
            if ($pivot->quantity > 1) {
                DB::table('deck_pokemon')
                    ->where('id', $pivot->id)
                    ->update(['quantity' => $pivot->quantity - 1, 'updated_at' => now()]);
            } else {
                $deck->pokemons()->detach($pokemonId);
            }
        }
        return redirect()->back()->with('status', 'Pokémon removed from deck.');
    }
}
