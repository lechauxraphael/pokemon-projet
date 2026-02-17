<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PokemonSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/pokemon.json');

        $json = File::get($path);
        $pokemons = json_decode($json, true);

        foreach ($pokemons as $pokemon) {
            DB::table('pokemon')->updateOrInsert(
                ['pokedex_number' => $pokemon['pokedex_number']],
                [
                    'pokedex_number' => $pokemon['pokedex_number'],
                    'name' => $pokemon['name'],
                    'generation' => $pokemon['generation'],
                    'is_legendary' => $pokemon['is_legendary'],
                    'type1' => $pokemon['type1'] ?? null,
                    'type2' => $pokemon['type2'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
