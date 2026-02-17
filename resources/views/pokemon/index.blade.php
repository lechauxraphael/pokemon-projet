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
    <p>Catalogue des Pokémon par génération</p>
</header>

<section class="grid">
@foreach($pokemons as $pokemon)
    <div class="card">
        <div class="number">#{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</div>

        <img 
            src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png"
            alt="{{ $pokemon->name }}"
        >

        <h3>{{ $pokemon->name }}</h3>

        <div class="badges">
            <span class="badge generation">
                Génération {{ $pokemon->generation }}
            </span>

            <span class="badge {{ $pokemon->is_legendary ? 'legendary' : 'normal' }}">
                {{ $pokemon->is_legendary ? 'Légendaire' : 'Normal' }}
            </span>
        </div>
    </div>
@endforeach
</section>

<footer>
    Projet Pokédex — Laravel
</footer>

</body>
</html>
