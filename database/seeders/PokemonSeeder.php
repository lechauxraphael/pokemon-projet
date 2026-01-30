<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PokemonSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('seeders/data/pokemon.json'));
        $pokemons = json_decode($json, true);

        foreach ($pokemons as $pokemon) {
            DB::table('pokemon')->insert([
                'pokedex_number' => $pokemon['pokedex_number'],
                'name' => $pokemon['name'],
                'generation' => $pokemon['generation'],
                'is_legendary' => $pokemon['is_legendary'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
