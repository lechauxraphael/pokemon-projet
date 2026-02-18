    @include('layouts.app')

    @section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pokédex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #e63946;
            --secondary: #1d3557;
            --light: #f1faee;
            --gray: #6c757d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #212529;
            padding: 40px;
        }

        header {
            margin-bottom: 40px;
            text-align: center;
        }

        header h1 {
            font-size: 2.8rem;
            color: var(--secondary);
            margin-bottom: 10px;
        }

        header p {
            color: var(--gray);
            font-size: 1.1rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: transform .2s ease, box-shadow .2s ease;
            text-align: center;
            display: flex;
            flex-direction: column;
            min-height: 300px;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 30px rgba(0,0,0,0.12);
        }

        .number {
            color: var(--gray);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .card img {
            width: 120px;
            height: 120px;
            margin: 10px auto;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 1.2rem;
            text-transform: capitalize;
            color: var(--secondary);
        }

        .badges {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .generation {
            background: #e9ecef;
            color: #495057;
        }

        .legendary {
            background: gold;
            color: #212529;
        }

        .normal {
            background: #dee2e6;
            color: #495057;
        }

        /* filter — select + buttons */
        .filters {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-form { display: inline-block; }
        .filter-control {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px 6px 12px;
            border-radius: 999px;
            border: 1px solid #e9ecef;
            background: white;
            box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        }
        .filters label { font-weight: 600; color: var(--gray); margin-right: 6px; }

        /* wrapper specifically for the select so caret sits over the select */
        .select-wrapper { position: relative; display: inline-block; }
        .select-wrapper::after {
            content: '▽';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #6c757d;
            pointer-events: none;
        }

        .filter-control select {
            border: none;
            background: transparent;
            padding: 8px 28px 8px 12px; /* réserver l'espace à droite pour ▽ */
            font-weight: 600;
            min-width: 160px;
            color: #495057;
            appearance: none;
        }
        .filter-control select:focus { outline: none; }

        .btn {
            padding: 8px 14px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: white; box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
        .btn-secondary { background: #f1f3f5; color: #495057; border: 1px solid #e9ecef; }

        .sr-only {
            position: absolute !important;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
        }

        .type-badge {
            background: #dee2e6;
            color: #495057;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Pokédex</h1>
    <p>Pokémon catalog by generation</p>
</header>

<div class="filters">
    <form method="GET" action="{{ route('pokemon') }}" class="filter-form" aria-label="Filtrer les Pokémon par type">
        <!-- search by name -->
        <div style="display: flex; gap: 8px; margin-bottom: 12px; justify-content: center;">
            <input type="text" name="search" placeholder="Search a Pokémon..." value="{{ request('search') }}" 
                   style="padding: 10px 14px; border: 1px solid #e9ecef; border-radius: 999px; min-width: 200px; font-weight: 600;">
            @if(request('search'))
                <a href="{{ route('pokemon', ['type' => request('type')]) }}" class="btn btn-secondary" style="padding: 10px 14px;" role="button">Clear</a>
            @endif
        </div>

        <label for="type-select" class="sr-only">Filter by type</label>
        <div class="filter-control">
            <div class="select-wrapper">
                <select name="type" id="type-select" aria-label="Type">
                    <option value="" {{ request('type') ? '' : 'selected' }}>All types</option>
                    @foreach(($types ?? []) as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                            {{ $typeLabels[$t] ?? ucfirst($t) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('pokemon') }}" class="btn btn-secondary" role="button">Reset</a>
        </div>
    </form>
</div>

<section class="grid">
@foreach($pokemons as $pokemon)
    <a href="{{ route('pokemon.show', $pokemon->id) }}" style="text-decoration: none; color: inherit;">
    <div class="card">
        <div class="number">#{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</div>

        @php
            $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower($pokemon->name));
            $base = trim($base, '-');
            $candidates = [$base . '.png', $base . '.jpg'];
            $localFile = null;
            foreach ($candidates as $c) {
                if (file_exists(public_path('images/' . $c))) { $localFile = $c; break; }
            }
        @endphp

        @if($localFile)
            <img src="{{ asset('images/' . $localFile) }}" alt="{{ $pokemon->name }}">
        @else
            <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png" alt="{{ $pokemon->name }}">
        @endif

        <h3>{{ $pokemon->name }}</h3>

        <div class="badges">
            @if($pokemon->type1)
                <span class="badge type-badge">{{ ucfirst($pokemon->type1) }}</span>
            @endif
            @if($pokemon->type2)
                <span class="badge type-badge">{{ ucfirst($pokemon->type2) }}</span>
            @endif


            @if($pokemon->is_legendary)
                <span class="badge legendary">
                    Legendary
                </span>
            @endif
        </div>
    </div>
    </a>
@endforeach
</section>

<!-- smart pagination -->
<div style="display: flex; justify-content: center; gap: 8px; margin: 40px 0; flex-wrap: wrap; align-items: center;">
    @if($pokemons->onFirstPage())
        <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 6px;">← Previous</span>
    @else
        <a href="{{ $pokemons->previousPageUrl() }}" style="padding: 8px 12px; background: #f1f3f5; color: #495057; text-decoration: none; border-radius: 6px;">← Previous</a>
    @endif

    @php
        $currentPage = $pokemons->currentPage();
        $lastPage = $pokemons->lastPage();
        $startPages = [1, 2, 3, 4, 5];
        $endPages = [$lastPage - 4, $lastPage - 3, $lastPage - 2, $lastPage - 1, $lastPage];
        $endPages = array_filter($endPages, fn($p) => $p > 0);
        $pagesShown = [];
    @endphp

    @foreach($startPages as $page)
        @if($page <= $lastPage)
            @php $pagesShown[] = $page; @endphp
            @if($page == $currentPage)
                <span style="padding: 8px 12px; background: var(--primary); color: white; border-radius: 6px; font-weight: 600;">{{ $page }}</span>
            @else
                <a href="{{ $pokemons->url($page) }}" style="padding: 8px 12px; background: #f1f3f5; color: #495057; text-decoration: none; border-radius: 6px;">{{ $page }}</a>
            @endif
        @endif
    @endforeach

    @if($lastPage > max($startPages) + 1 && min($endPages) > max($startPages) + 1)
        <span style="padding: 8px 4px; color: #6c757d;">…</span>
    @endif

    @foreach($endPages as $page)
        @if($page > max($startPages) && !in_array($page, $pagesShown))
            @php $pagesShown[] = $page; @endphp
            @if($page == $currentPage)
                <span style="padding: 8px 12px; background: var(--primary); color: white; border-radius: 6px; font-weight: 600;">{{ $page }}</span>
            @else
                <a href="{{ $pokemons->url($page) }}" style="padding: 8px 12px; background: #f1f3f5; color: #495057; text-decoration: none; border-radius: 6px;">{{ $page }}</a>
            @endif
        @endif
    @endforeach

    @if($pokemons->hasMorePages())
        <a href="{{ $pokemons->nextPageUrl() }}" style="padding: 8px 12px; background: #f1f3f5; color: #495057; text-decoration: none; border-radius: 6px;">Next →</a>
    @else
        <span style="padding: 8px 12px; background: #e9ecef; color: #6c757d; border-radius: 6px;">Next →</span>
    @endif
</div>

<footer>
    PokemonFactory © {{ date('Y') }} - All rights reserved
</footer>

</body>
</html>
