<?php
        $path = __DIR__ . "/data/fichier.json";
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString, true);
        foreach ($jsonData as $key => $record) {
            Pokemon::firstOrCreate([
                'pokedex_number' => $record['pokedex_number'],
                'name' => $record['name'],
                'generation' => $record['generation'],
                'is_legendary' => $record['is_legendary'],
                'attack' => $record['attack'],
                'defense' => $record['defense'],
            ]);
        }
