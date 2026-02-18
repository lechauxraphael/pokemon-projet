@extends('layouts.app')

@section('content')
    <style>
        :root{ --bg:#f8f9fa; --card:#fff; --muted:#6c757d; }
        .wrap{ max-width:1000px; margin:0 auto; padding:32px 0 }
        .back{ text-decoration:none; color:#495057; display:inline-block; margin-bottom:18px; font-weight:600 }
        .card{ display:flex; gap:28px; background:var(--card); padding:28px; border-radius:14px; box-shadow:0 14px 35px rgba(15,15,15,0.07); align-items:center }
        .left{ width:320px; text-align:center }
        .figure{ background:linear-gradient(180deg, rgba(0,0,0,0.02), rgba(0,0,0,0.01)); border-radius:12px; padding:18px }
        .figure img{ width:230px; height:230px; object-fit:contain }
        .right{ flex:1 }
        h1{ margin:0; font-size:2.2rem; text-transform:capitalize; display:flex; gap:12px; align-items:center }
        .number{ color:var(--muted); font-weight:700; font-size:0.9rem }
        .meta{ color:var(--muted); margin:10px 0 16px }
        .badges{ display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px }
        .badge{ padding:8px 12px; border-radius:999px; font-weight:700; color:#fff }
        .info-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:12px }
        .info{ background:#f1f3f5; padding:12px; border-radius:8px; text-align:center }
        .info strong{ display:block; font-size:1.1rem }
        .desc{ color:#495057; line-height:1.5; margin-top:14px }
        @media (max-width:880px){ .card{ flex-direction:column; } .left{ width:100% } .info-grid{ grid-template-columns:repeat(2,1fr) } }
    </style>

    <div class="wrap">
        <a href="{{ route('pokemon') }}" class="back">← Retour au Pokédex</a>

        @php
            $typeColors = [
                'fire' => '#F08030','water' => '#6890F0','grass' => '#78C850','electric' => '#F8D030',
                'ice' => '#98D8D8','fighting' => '#C03028','poison' => '#A040A0','ground' => '#E0C068',
                'flying' => '#A890F0','psychic' => '#F85888','bug' => '#A8B820','rock' => '#B8A038',
                'ghost' => '#705898','dark' => '#705848','dragon' => '#7038F8','steel' => '#B8B8D0','fairy' => '#EE99AC',
            ];

            $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($pokemon->name));
            $base = trim($base, '-');
            $candidates = [$base . '.png', $base . '.jpg'];
            $localFile = null;
            foreach ($candidates as $c) { if (file_exists(public_path('images/' . $c))) { $localFile = $c; break; } }
        @endphp

        <div class="card">
            <div class="left">
                <div class="figure">
                    @if($localFile)
                        <img src="{{ asset('images/' . $localFile) }}" alt="{{ $pokemon->name }}">
                    @else
                        <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png" alt="{{ $pokemon->name }}">
                    @endif
                </div>
            </div>

            <div class="right">
                <h1>{{ $pokemon->name }} <span class="number">#{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</span></h1>

                <div class="badges">
                    @if($pokemon->type1)
                        @php $c = $typeColors[$pokemon->type1] ?? '#6c757d'; @endphp
                        <span class="badge" style="background:{{ $c }}">{{ ucfirst($pokemon->type1) }}</span>
                    @endif
                    @if($pokemon->type2)
                        @php $c2 = $typeColors[$pokemon->type2] ?? '#6c757d'; @endphp
                        <span class="badge" style="background:{{ $c2 }}">{{ ucfirst($pokemon->type2) }}</span>
                    @endif
                    @if($pokemon->is_legendary)
                        <span class="badge" style="background:#FFD166">Legendary</span>
                    @endif
                </div>

                <div class="meta">Generation {{ $pokemon->generation }} • Enregistré le {{ optional($pokemon->created_at)->format('d/m/Y') ?? '—' }}</div>

                <p class="desc">Cette fiche affiche les informations de base disponibles : numéro Pokédex, types, génération et statut légendaire. Vous pouvez stocker des descriptions, statistiques ou attaques dans la base et les afficher ici.</p>

                <div class="info-grid">
                    <div class="info"><small>Numéro</small><strong>#{{ $pokemon->pokedex_number }}</strong></div>
                    <div class="info"><small>Génération</small><strong>{{ $pokemon->generation }}</strong></div>
                    <div class="info"><small>Légendaire</small><strong>{{ $pokemon->is_legendary ? 'Oui' : 'Non' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

@endsection
