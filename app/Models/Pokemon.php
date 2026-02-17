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
    ];
}


