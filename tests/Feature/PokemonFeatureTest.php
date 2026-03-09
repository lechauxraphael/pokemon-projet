<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function the_pokedex_page_is_displayed()
    {
        $this->get('/pokemon')->assertStatus(200)->assertSee('Pokédex');
    }

    /** @test */
    public function the_pokedex_displays_a_list_of_pokemon()
    {
        $this->get('/pokemon')->assertSee('Bulbasaur');
    }

    /** @test */
    public function a_user_can_search_for_a_pokemon()
    {
        $this->get('/pokemon?search=Pikachu')->assertSee('Pikachu')->assertDontSee('Bulbasaur');
    }

    /** @test */
    public function a_user_can_filter_pokemon_by_type()
    {
        $this->get('/pokemon?type=fire')->assertSee('Charmander')->assertDontSee('Squirtle');
    }

    /** @test */
    public function the_pokemon_detail_page_is_displayed()
    {
        $pokemon = Pokemon::first();
        $this->get('/pokemon/' . $pokemon->pokedex_number)->assertStatus(200)->assertSee($pokemon->name);
    }

    /** @test */
    public function the_pokedex_is_paginated()
    {
        $this->get('/pokemon')->assertSee('pagination');
    }
}
