<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_without_remember_me_and_lock_screen(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('Remember me');
        $response->assertDontSee('Use lock screen');
        $response->assertSee('Username atau Email');
    }

    public function test_register_page_renders(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('Create an account');
    }

    public function test_user_can_register(): void
    {
        $response = $this->post(route('registerproses'), [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'roles' => 'staff',
        ]);
    }

    public function test_user_can_login_with_username(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'adminuser@example.com',
            'roles' => 'admin',
            'password' => 'password123',
        ]);

        $response = $this->post(route('loginproses'), [
            'login' => 'adminuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('modul'));
        $this->assertAuthenticatedAs($user);

        $modulResponse = $this->get(route('modul'));
        $modulResponse->assertOk();
        $modulResponse->assertSee('Daftar Modul');
        $modulResponse->assertSee('SIM Kepegawaian');
        $modulResponse->assertSee('Daftar Role');
        $modulResponse->assertSee('Admin');
        $modulResponse->assertSee(route('dashboard'));

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_user_can_login_with_email(): void
    {
        $user = User::create([
            'name' => 'Staff User',
            'username' => 'staffuser',
            'email' => 'staffuser@example.com',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $response = $this->post(route('loginproses'), [
            'login' => 'staffuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('modul'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Staff User',
            'username' => 'staffuser',
            'email' => 'staffuser@example.com',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $response = $this->post(route('loginproses'), [
            'login' => 'staffuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Staff User',
            'username' => 'staffuser',
            'email' => 'staffuser@example.com',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
