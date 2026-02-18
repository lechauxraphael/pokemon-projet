@extends('layouts.app')

@section('content')
    <style>
        :root { --bg: #f8f9fa; --card: #fff; --muted: #6c757d; --primary: #e63946; }
        
        body { background: var(--bg); text-align: center;}

        .titlePage {text-align: center;}
        
        .pokemon-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 30px;
            transition: gap 0.2s ease;
        }
        
        .back-btn:hover {
            gap: 12px;
        }
        
        .pokemon-card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
            align-items: center;
        }
        
        .pokemon-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .pokemon-image-wrapper {
            width: 280px;
            height: 280px;
            background: linear-gradient(135deg, rgba(230, 57, 70, 0.08) 0%, rgba(29, 53, 87, 0.08) 100%);
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .pokemon-image img {
            width: 240px;
            height: 240px;
            object-fit: contain;
        }
        
        .pokemon-info h1 {
            margin: 0 0 8px 0;
            font-size: 2.8rem;
            text-transform: capitalize;
            color: #212529;
        }
        
        .pokemon-number {
            color: var(--muted);
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 16px;
        }
        
        .pokemon-types {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .type-badge {
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 700;
            color: white;
            font-size: 0.9rem;
        }
        
        .pokemon-meta {
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .pokemon-description {
            color: #495057;
            line-height: 1.6;
            margin-bottom: 28px;
            font-size: 0.95rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        
        .info-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .info-item-label {
            display: block;
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .info-item-value {
            display: block;
            font-size: 1.2rem;
            font-weight: 700;
            color: #212529;
        }
        
        .stats-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #f0f0f0;
        }
        
        .stats-section h2 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            text-align: left;
            color: #212529;
        }
        
        .stat-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .stat-label {
            width: 120px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #495057;
        }
        
        .stat-bar-bg {
            flex: 1;
            height: 24px;
            background: #f0f0f0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .stat-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #e63946 0%, #f77f88 100%);
            border-radius: 12px;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
        }
        
        @media (max-width: 768px) {
            .pokemon-card {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 30px 20px;
            }
            
            .pokemon-info h1 {
                font-size: 2rem;
            }
            
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stat-label {
                width: 100px;
                font-size: 0.85rem;
            }
        }
    </style>

    <div class="pokemon-container">
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
            foreach ($candidates as $c) { if (file_exists(public_path('images/' . $c))) { $localFile = $c; break; } }
        @endphp

        <h1 class="titlePage">You're on the page of {{ $pokemon->name }}</h1>

        <div class="pokemon-card">
            <div class="pokemon-image">
                <div class="pokemon-image-wrapper">
                    @if($localFile)
                        <img src="{{ asset('images/' . $localFile) }}" alt="{{ $pokemon->name }}">
                    @else
                        <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png" alt="{{ $pokemon->name }}">
                    @endif
                </div>
            </div>

            <div class="pokemon-info">
                <h1>{{ $pokemon->name }}</h1>
                <div class="pokemon-number">Number #{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</div>

                <div class="pokemon-types">Type(s) : 
                    @if($pokemon->type1)
                        @php $c = $typeColors[$pokemon->type1] ?? '#6c757d'; @endphp
                        <span class="type-badge" style="background: {{ $c }}">{{ ucfirst($pokemon->type1) }}</span>
                    @endif
                    @if($pokemon->type2)
                        @php $c2 = $typeColors[$pokemon->type2] ?? '#6c757d'; @endphp
                        <span class="type-badge" style="background: {{ $c2 }}">{{ ucfirst($pokemon->type2) }}</span>
                    @endif
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-item-label">Generation : </span>
                        <span class="info-item-value">{{ $pokemon->generation }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Legendary : </span>
                        <span class="info-item-value">{{ $pokemon->is_legendary ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Weight : </span>
                        <span class="info-item-value">{{ $pokemon->weight_kg ?? 'N/A' }} kg</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Height : </span>
                        <span class="info-item-value">-</span>
                    </div>
                </div>

                <div class="stats-section">
                    <h2>Stats</h2>
                    
                    @php
                        $stats = [
                            ['name' => 'HP', 'value' => $pokemon->hp ?? 0],
                            ['name' => 'Attack', 'value' => $pokemon->attack ?? 0],
                            ['name' => 'Defense', 'value' => $pokemon->defense ?? 0],
                            ['name' => 'Sp. Attack', 'value' => $pokemon->sp_attack ?? 0],
                            ['name' => 'Sp. Defense', 'value' => $pokemon->sp_defense ?? 0],
                            ['name' => 'Speed', 'value' => $pokemon->speed ?? 0],
                        ];
                    @endphp
                    
                    @foreach($stats as $stat)
                        @php
                            $percentage = min(($stat['value'] / 160) * 100, 100);
                        @endphp
                        <div class="stat-bar">
                            <div class="stat-label">{{ $stat['name'] }}</div>
                            <div class="stat-bar-bg">
                                <div class="stat-bar-fill" style="width: {{ $percentage }}%;">
                                    {{ $stat['value'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
