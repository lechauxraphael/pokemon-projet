<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function a_user_can_create_a_deck()
    {
        $this->post('/deck/store', ['name' => 'My Awesome Deck']);
        $this->assertDatabaseHas('decks', ['name' => 'My Awesome Deck']);
    }

    /** @test */
    public function a_user_can_rename_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $this->post("/deck/{$deck->id}/rename", ['name' => 'My Renamed Deck']);
        $this->assertDatabaseHas('decks', ['id' => $deck->id, 'name' => 'My Renamed Deck']);
    }

    /** @test */
    public function a_user_can_delete_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $this->post("/deck/{$deck->id}/delete");
        $this->assertDatabaseMissing('decks', ['id' => $deck->id]);
    }

    /** @test */
    public function a_user_can_add_a_pokemon_to_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $pokemon = Pokemon::first();
        $this->post('/deck/add-pokemon', ['deck_id' => $deck->id, 'pokedex_number' => $pokemon->pokedex_number]);
        $this->assertDatabaseHas('deck_pokemon', ['deck_id' => $deck->id, 'pokemon_id' => $pokemon->id]);
    }

    /** @test */
    public function a_user_cannot_add_more_than_6_pokemon_to_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $pokemons = Pokemon::take(7)->get();
        foreach ($pokemons as $pokemon) {
            $this->post('/deck/add-pokemon', ['deck_id' => $deck->id, 'pokedex_number' => $pokemon->pokedex_number]);
        }
        $this->assertEquals(6, $deck->pokemons()->count());
    }

    /** @test */
    public function a_user_can_remove_a_pokemon_from_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $pokemon = Pokemon::first();
        $deck->pokemons()->attach($pokemon);
        $this->post("/deck/{$deck->id}/remove-pokemon", ['pokemon_id' => $pokemon->id]);
        $this->assertDatabaseMissing('deck_pokemon', ['deck_id' => $deck->id, 'pokemon_id' => $pokemon->id]);
    }

    /** @test */
    public function a_user_can_add_the_same_pokemon_multiple_times()
    {
        $deck = Deck::factory()->create(['user_id' => auth()->id()]);
        $pokemon = Pokemon::first();
        $this->post('/deck/add-pokemon', ['deck_id' => $deck->id, 'pokedex_number' => $pokemon->pokedex_number]);
        $this->post('/deck/add-pokemon', ['deck_id' => $deck->id, 'pokedex_number' => $pokemon->pokedex_number]);
        $this->assertEquals(2, $deck->pokemons()->first()->pivot->quantity);
    }
}
