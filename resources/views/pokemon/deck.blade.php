@extends('layouts.app')

@section('content')
    <div class="pokemon-page">
        <h1 class="titlePage">Your Deck ({{ count(session('deck', [])) }}/6)</h1>
        <div class="pokemon-wrapper">
            <a href="{{ route('pokemon') }}" class="back-btn">← Back to Pokédex</a>

            @if(session('status'))
                <div class="alert alert-success" style="margin-top:12px">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" style="margin-top:12px">{{ session('error') }}</div>
            @endif

            <div style="margin-top:16px;padding:12px;border:1px solid #e9ecef;border-radius:8px;background:#fff;">
                <form method="POST" action="{{ route('deck.store') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Nom du deck" required
                               style="padding:8px 12px;border:1px solid #e9ecef;border-radius:6px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Créer un deck</button>
                </form>
                @if(($decks ?? collect())->isNotEmpty())
                    <a href="{{ route('pokemon') }}" class="btn btn-secondary" style="margin-left:8px;margin-top:8px;display:inline-block">Choisir mes pokémons</a>
                @endif
            </div>

            @if($pokemons->isEmpty())
                <p style="margin-top:16px">Your deck is empty. Add up to 6 Pokémon from a Pokémon's page.</p>
            @else
                <div class="deck-list" style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px">
                    @foreach($pokemons as $p)
                        @php
                            $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($p->name));
                            $base = trim($base, '-');
                            $candidate = file_exists(public_path('images/' . $base . '.png')) ? asset('images/' . $base . '.png') : 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $p->pokedex_number . '.png';
                        @endphp
                        <div class="deck-item" style="width:140px;border:1px solid #eee;padding:8px;border-radius:8px;text-align:center;background:#fff">
                            <img src="{{ $candidate }}" alt="{{ $p->name }}" style="width:96px;height:96px;object-fit:contain">
                            <div style="font-weight:600;margin-top:6px">{{ $p->name }}</div>
                            <div style="font-size:13px;color:#666">#{{ str_pad($p->pokedex_number,3,'0',STR_PAD_LEFT) }}</div>
                            <div style="margin-top:8px">
                                <a href="{{ route('pokemon.show', $p->pokedex_number) }}" class="btn btn-sm btn-outline-primary" style="margin-bottom:6px;display:inline-block">View</a>
                                <form method="POST" action="{{ route('deck.remove', $p->pokedex_number) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div style="margin-top:24px">
                <h2 style="font-size:20px;margin-bottom:8px">Saved Decks</h2>
                @if(($decks ?? collect())->isEmpty())
                    <p>Aucun deck pour le moment.</p>
                @else
                    <div style="display:flex;flex-direction:column;gap:10px">
                        @foreach($decks as $d)
                            <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e9ecef;border-radius:8px;padding:10px;background:#fff">
                                <div>
                                    <strong>{{ $d->name }}</strong>
                                    <span style="color:#666;margin-left:6px">({{ $d->pokemons_count }} Pokémon)</span>
                                </div>
                                <div style="display:flex;gap:8px;align-items:center">
                                    <form method="POST" action="{{ route('deck.rename', $d->id) }}" style="display:flex;gap:6px;align-items:center">
                                        @csrf
                                        <input type="text" name="name" value="{{ $d->name }}" required
                                               style="padding:6px 10px;border:1px solid #e9ecef;border-radius:6px;">
                                        <button type="submit" class="btn btn-secondary">Rename</button>
                                    </form>
                                    <form method="POST" action="{{ route('deck.delete', $d->id) }}" onsubmit="return confirm('Delete this deck?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
