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
        </div>
    </div>
@endsection
