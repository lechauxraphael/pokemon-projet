@extends('layouts.app')

@push('head')
    @vite(['resources/css/pokemon-show.css'])
@endpush

@section('content')
        
    <div class="pokemon-page">
        <h1 class="titlePage"> You're on the {{ $pokemon->name }} page</h1>
        <div class="pokemon-wrapper">
            <a href="{{ route('pokemon') }}" class="back-btn">← Back to Pokédex</a>

            @php
                $typeColors = [
                    'fire' => '#F08030', 'water' => '#6890F0', 'grass' => '#78C850', 'electric' => '#F8D030',
                    'ice' => '#98D8D8', 'fighting' => '#C03028', 'poison' => '#A040A0', 'ground' => '#E0C068',
                    'flying' => '#A890F0', 'psychic' => '#F85888', 'bug' => '#A8B820', 'rock' => '#B8A038',
                    'ghost' => '#705898', 'dark' => '#705848', 'dragon' => '#7038F8', 'steel' => '#B8B8D0', 'fairy' => '#EE99AC',
                ];

                $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($pokemon->name));
                $base = trim($base, '-');
                $candidates = [$base . '.png', $base . '.jpg'];
                $localFile = null;
                foreach ($candidates as $c) {
                    if (file_exists(public_path('images/' . $c))) {
                        $localFile = $c;
                        break;
                    }
                }
            @endphp

            <h1 class="pokemon-title">{{ $pokemon->name }}</h1>
            <div class="pokemon-number">Number #{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</div>

            <div class="pokemon-image-container">
                @if($localFile)
                    <img src="{{ asset('images/' . $localFile) }}" alt="{{ $pokemon->name }}" class="pokemon-image">
                @else
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png" alt="{{ $pokemon->name }}" class="pokemon-image">
                @endif
            </div>

            <div class="pokemon-types">
                <span class="type-label">Type(s) :</span>
                @if($pokemon->type1)
                    @php $color = $typeColors[$pokemon->type1] ?? '#6c757d'; @endphp
                    <span class="type-badge" style="background-color: {{ $color }};">{{ ucfirst($pokemon->type1) }}</span>
                @endif
                @if($pokemon->type2)
                    @php $color = $typeColors[$pokemon->type2] ?? '#6c757d'; @endphp
                    <span class="type-badge" style="background-color: {{ $color }};">{{ ucfirst($pokemon->type2) }}</span>
                @endif
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Generation</span>
                    <span class="info-value">{{ $pokemon->generation }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Legendary</span>
                    <span class="info-value">{{ $pokemon->is_legendary ? 'Yes' : 'No' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Weight</span>
                    <span class="info-value">
                        @if(is_numeric($pokemon->weight_kg) && $pokemon->weight_kg > 0)
                            {{ $pokemon->weight_kg }} kg
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Height</span>
                    <span class="info-value">
                        @if($pokemon->height_m > 0)
                            {{ $pokemon->height_m }} m
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>

            <div class="stats-section">
                <h2 class="stats-title">Stats</h2>

                @php
                    $stats = [
                        ['name' => 'HP', 'value' => $pokemon->hp ?? null],
                        ['name' => 'Attack', 'value' => $pokemon->attack ?? null],
                        ['name' => 'Defense', 'value' => $pokemon->defense ?? null],
                        ['name' => 'Sp. Attack', 'value' => $pokemon->sp_attack ?? null],
                        ['name' => 'Sp. Defense', 'value' => $pokemon->sp_defense ?? null],
                        ['name' => 'Speed', 'value' => $pokemon->speed ?? null],
                    ];
                @endphp

                @foreach($stats as $stat)
                    @php
                        $val = $stat['value'];
                        $has = is_numeric($val) && $val > 0;
                        $percentage = $has ? min(($val / 160) * 100, 100) : 0;
                    @endphp
                    <div class="stat-item">
                        <div class="stat-name">{{ $stat['name'] }}</div>
                        <div class="stat-bar-container">
                            <div class="stat-bar-fill" style="width: {{ $percentage }}%;">
                                {{ $has ? $val : 'N/A' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
