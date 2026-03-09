<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemon';

    protected $fillable = [
        'pokedex_number',
        'name',
        'generation',
        'is_legendary',
        'type1',
        'type2',
        'hp',
        'attack',
        'defense',
        'sp_attack',
        'sp_defense',
        'speed',
        'weight_kg',
        'height_m',
    ];

    public function decks()
    {
        return $this->belongsToMany(Deck::class, 'deck_pokemon', 'pokemon_id', 'deck_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}

