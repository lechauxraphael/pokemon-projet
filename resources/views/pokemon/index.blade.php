    @include('layouts.app')
    @section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pokédex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/pokemon-index.css'])
</head>
<body>
<header>
    <h1>Pokédex</h1>
    <p>Pokémon catalog by generation</p>
    <p>Clic on the pokemon to see its details</p>
</header>

<div class="filters">
    <form method="GET" action="{{ route('pokemon') }}" class="filter-form" aria-label="Filtrer les Pokémon par type">
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
        </div></br>
                <!-- search by name -->
        <div class="search-row">
            <input type="text" name="search" placeholder="Search a Pokémon..." value="{{ request('search') }}" class="search-input">
            @if(request('search'))
                <a href="{{ route('pokemon', ['type' => request('type')]) }}" class="btn btn-secondary" role="button">Clear</a>
            @endif
        </div>
    </form>
</div>

<section class="grid">
@foreach($pokemons as $pokemon)
    <a href="{{ route('pokemon.show', $pokemon->pokedex_number) }}" class="card-link">
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
<div class="pagination-bar">
    @if($pokemons->onFirstPage())
        <span class="page-link--disabled">← Previous</span>
    @else
        <a href="{{ $pokemons->previousPageUrl() }}" class="page-link">← Previous</a>
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
                <span class="page-link--active">{{ $page }}</span>
            @else
                <a href="{{ $pokemons->url($page) }}" class="page-link">{{ $page }}</a>
            @endif
        @endif
    @endforeach

    @if($lastPage > max($startPages) + 1 && min($endPages) > max($startPages) + 1)
        <span style="color: #6c757d;">…</span>
    @endif

    @foreach($endPages as $page)
        @if($page > max($startPages) && !in_array($page, $pagesShown))
            @php $pagesShown[] = $page; @endphp
            @if($page == $currentPage)
                <span class="page-link--active">{{ $page }}</span>
            @else
                <a href="{{ $pokemons->url($page) }}" class="page-link">{{ $page }}</a>
            @endif
        @endif
    @endforeach

    @if($pokemons->hasMorePages())
        <a href="{{ $pokemons->nextPageUrl() }}" class="page-link">Next →</a>
    @else
        <span class="page-link--disabled">Next →</span>
    @endif
</div>

<footer>
    PokemonFactory © {{ date('Y') }} - All rights reserved
</footer>

</body>
</html>
