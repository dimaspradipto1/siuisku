<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@uis.ac.id',
            'roles' => 'super admin',
            'password' => 'password123',
        ]);

        $this->actingAs($admin);

        return $admin;
    }

    public function test_users_index_create_show_and_edit_pages_render(): void
    {
        $admin = $this->actingAsAdmin();

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee('People Directory')
            ->assertSee('users-table');

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('users.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1)
            ->assertJsonFragment(['id' => $admin->id]);

        $this->get(route('users.create'))
            ->assertOk()
            ->assertSee('Tambah User');

        $this->get(route('users.show', $admin))
            ->assertOk()
            ->assertSee('User Command Profile')
            ->assertSee($admin->email);

        $this->get(route('users.edit', $admin))
            ->assertOk()
            ->assertSee('Edit User')
            ->assertSee($admin->username);
    }

    public function test_can_store_update_and_delete_user(): void
    {
        $admin = $this->actingAsAdmin();

        $storeResponse = $this->post(route('users.store'), [
            'name' => 'Siti Aminah',
            'username' => 'sitiaminah',
            'email' => 'siti@uis.ac.id',
            'roles' => 'staff',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $storeResponse->assertRedirect(route('users.index'));
        $created = User::where('username', 'sitiaminah')->firstOrFail();

        $updateResponse = $this->put(route('users.update', $created), [
            'name' => 'Siti Aminah, S.Kom',
            'username' => 'sitiaminah',
            'email' => 'siti.aminah@uis.ac.id',
            'roles' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $updateResponse->assertRedirect(route('users.index'));
        $created->refresh();
        $this->assertSame('Siti Aminah, S.Kom', $created->name);
        $this->assertSame('admin', $created->roles);
        $this->assertTrue(Hash::check('secret123', $created->password));

        $selfDeleteResponse = $this->delete(route('users.destroy', $admin));
        $selfDeleteResponse->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        $deleteResponse = $this->delete(route('users.destroy', $created));
        $deleteResponse->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $created->id]);
    }

    public function test_user_without_photo_uses_initials_svg_avatar(): void
    {
        $user = User::create([
            'name' => 'Dr. Andi Auliya Ramadhany',
            'username' => 'auliya',
            'email' => 'auliya@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $this->assertSame('AA', $user->initials);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $user->avatar_url);
    }
}
