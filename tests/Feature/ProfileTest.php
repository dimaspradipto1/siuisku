<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_and_update_own_profile(): void
    {
        $user = User::create([
            'name' => 'Dimas Pradipto',
            'username' => 'dimas',
            'email' => 'dimas@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $this->actingAs($user);

        $this->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Dimas Pradipto')
            ->assertSee('dimas@uis.ac.id');

        $response = $this->put(route('profile.update'), [
            'name' => 'Dimas Pradipto, S.Kom',
            'username' => 'dimas_p',
            'email' => 'dimas.p@uis.ac.id',
        ]);

        $response->assertRedirect(route('profile.index'));
        $user->refresh();
        $this->assertSame('Dimas Pradipto, S.Kom', $user->name);
        $this->assertSame('dimas_p', $user->username);
        $this->assertSame('staff', $user->roles);
    }

    public function test_authenticated_user_can_update_own_password(): void
    {
        $user = User::create([
            'name' => 'Dimas Pradipto',
            'username' => 'dimas',
            'email' => 'dimas@uis.ac.id',
            'roles' => 'staff',
            'password' => 'oldpassword',
        ]);

        $this->actingAs($user);

        $this->put(route('profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertSessionHasErrors('current_password');

        $this->put(route('profile.password'), [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertRedirect(route('profile.index'));

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}
