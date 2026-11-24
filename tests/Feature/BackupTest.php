<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $user = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@uis.ac.id',
            'roles' => 'super admin',
            'password' => 'password123',
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_backup_page_renders(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('pengaturan.backup.index'));

        $response->assertOk();
        $response->assertSee('Backup Database &amp; Storage', false);
        $response->assertSee('Ekspor &amp; Impor Database', false);
        $response->assertSee('Backup &amp; Impor Storage Public', false);
    }

    public function test_can_export_database_sql_and_storage_zip(): void
    {
        $this->actingAsAdmin();

        $sqlResponse = $this->get(route('pengaturan.backup.database.export'));
        $sqlResponse->assertOk();
        $sqlResponse->assertHeader('content-type', 'application/sql; charset=UTF-8');

        $zipResponse = $this->get(route('pengaturan.backup.storage.export'));
        $zipResponse->assertOk();
        $this->assertStringContainsString('.zip', (string) $zipResponse->headers->get('content-disposition'));
    }
}
