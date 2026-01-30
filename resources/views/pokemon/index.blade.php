<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pokédex</title>
    <style>
        .grid { display: grid; grid-template-columns: repeat(auto-fill, 200px); gap: 20px; }
        .card { border: 1px solid #ccc; padding: 10px; text-align: center; }
    </style>
</head>
<body>

<h1>📖 Pokédex</h1>

<div class="grid">
@foreach($pokemons as $pokemon)
    <div class="card">
        <h3>#{{ $pokemon->pokedex_number }} {{ $pokemon->name }}</h3>

        <img 
            src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $pokemon->pokedex_number }}.png"
            alt="{{ $pokemon->name }}"
        >

        <p>🧬 Génération : {{ $pokemon->generation }}</p>
        <p>
            ⭐ Légendaire :
            {{ $pokemon->is_legendary ? 'Oui' : 'Non' }}
        </p>
    </div>
@endforeach
</div>

</body>
</html>
