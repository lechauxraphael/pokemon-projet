<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Deck extends Model
{
    protected $table = 'decks';

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'deck_pokemon', 'deck_id', 'pokemon_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
