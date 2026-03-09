<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_view_the_login_page()
    {
        $this->get('/')->assertStatus(200)->assertViewIs('auth.login');
    }

    /** @test */
    public function an_authenticated_user_is_redirected_from_the_login_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/login')->assertRedirect('/pokemon');
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/pokemon');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function a_guest_is_redirected_from_pokemon_pages_to_login()
    {
        $this->get('/pokemon')->assertRedirect('/login');
        $this->get('/deck')->assertRedirect('/login');
    }
}
