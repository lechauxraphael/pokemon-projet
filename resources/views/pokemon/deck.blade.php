@extends('layouts.app')

@push('head')
    @vite(['resources/css/decks.css'])
@endpush

@section('content')
    <div class="pokemon-page">
        <h1 class="titlePage">My Deck(s) </h1>
        <div class="pokemon-wrapper">
            <a href="{{ route('pokemon') }}" class="back-btn">← Back to Pokédex</a>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="deck-create">
                <form method="POST" action="{{ route('deck.store') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Nom du deck" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Deck</button>
                </form>
            </div>

                <div class="deck-list">
                    @foreach($pokemons as $p)
                        @php
                            $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($p->name));
                            $base = trim($base, '-');
                            $candidate = file_exists(public_path('images/' . $base . '.png')) ? asset('images/' . $base . '.png') : 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $p->pokedex_number . '.png';
                        @endphp
                        <div class="deck-item">
                            <img src="{{ $candidate }}" alt="{{ $p->name }}">
                            <div class="deck-item-name">{{ $p->name }}</div>
                            <div class="deck-item-number">#{{ str_pad($p->pokedex_number,3,'0',STR_PAD_LEFT) }}</div>
                            <div class="action-row">
                                <a href="{{ route('pokemon.show', $p->pokedex_number) }}" class="btn btn-sm btn-outline-primary mb-6 inline-block">View</a>
                                <form method="POST" action="{{ route('deck.remove', $p->pokedex_number) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>


            <div style="margin-top:24px">
                <h2 style="font-size:20px;margin-bottom:8px">Saved Decks</h2>
                @if(($decks ?? collect())->isEmpty())
                    <p>No decks for the moment.</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:10px">
                        @foreach($decks as $d)
                            <div class="saved-deck">
                                <div class="saved-deck__header">
                                    <div>
                                        <strong>{{ $d->name }}</strong>
                                        <span class="saved-deck__count">({{ $d->items_count ?? 0 }} Pokémon)</span>
                                        <a href="{{ route('pokemon') }}" class="btn btn-secondary ml-8 inline-block">Choose my Pokémon</a>
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:center">
                                        <form method="POST" action="{{ route('deck.rename', $d->id) }}" style="display:flex;gap:6px;align-items:center">
                                            @csrf
                                            <input type="text" name="name" value="{{ $d->name }}" required
                                                   style="padding:6px 10px;border:1px solid #e9ecef;border-radius:6px;">
                                            <button type="submit" class="btn btn-secondary">Rename</button>
                                        </form>
                                        <form method="POST" action="{{ route('deck.delete', $d->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                @if($d->pokemons->isNotEmpty())
                                    <div class="deck-list">
                                        @foreach($d->pokemons as $p)
                                            @php $qty = $p->pivot->quantity ?? 1; @endphp
                                            @for($i = 0; $i < $qty; $i++)
                                            @php
                                                $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($p->name));
                                                $base = trim($base, '-');
                                                $candidate = file_exists(public_path('images/' . $base . '.png')) ? asset('images/' . $base . '.png') : 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $p->pokedex_number . '.png';
                                            @endphp
                                            <div class="deck-item">
                                                <img src="{{ $candidate }}" alt="{{ $p->name }}">
                                                <div class="deck-item-name">{{ $p->name }}</div>
                                                <div class="deck-item-number">#{{ str_pad($p->pokedex_number,3,'0',STR_PAD_LEFT) }}</div>
                                                <div class="action-row">
                                                    <a href="{{ route('pokemon.show', $p->pokedex_number) }}" class="btn btn-sm btn-outline-primary mb-6 inline-block">View</a>
                                                    <form method="POST" action="{{ route('deck.remove_pokemon', $d->id) }}" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="pokemon_id" value="{{ $p->id }}">
                                                        <button type="submit" class="btn btn-sm btn-secondary">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                            @endfor
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
