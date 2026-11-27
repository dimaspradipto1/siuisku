<?php

namespace Tests\Feature;

use App\Models\JenisJabatanStruktural;
use App\Models\Modul;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\UserRole;
use Database\Seeders\HakAksesSeeder;
use Database\Seeders\JenisJabatanStrukturalSeeder;
use Database\Seeders\ModulSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HakAksesTest extends TestCase
{
    use RefreshDatabase;

    private function seedOtkData(): User
    {
        $this->seed([
            ModulSeeder::class,
            JenisJabatanStrukturalSeeder::class,
            UnitKerjaSeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@uis.ac.id',
            'roles' => 'super admin',
            'password' => 'password123',
        ]);

        $this->seed(HakAksesSeeder::class);
        $this->actingAs($admin);

        return $admin;
    }

    public function test_unit_kerja_crud_and_datatable(): void
    {
        $this->seedOtkData();

        $this->get(route('unit-kerja.index'))
            ->assertOk()
            ->assertSee('Unit Kerja')
            ->assertSee('LaravelDataTables["unit-kerja-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('unit-kerja.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 19);

        $uis = UnitKerja::where('kode', '171033')->firstOrFail();

        $this->post(route('unit-kerja.store'), [
            'kode' => 'bauk',
            'nama' => 'Biro Administrasi Umum dan Keuangan',
            'jenis' => 'biro',
            'parent_id' => $uis->id,
        ])->assertRedirect(route('unit-kerja.index'));

        $unit = UnitKerja::where('kode', 'BAUK')->firstOrFail();

        $this->put(route('unit-kerja.update', $unit), [
            'kode' => 'BAUK',
            'nama' => 'BAUK Universitas Ibnu Sina',
            'jenis' => 'biro',
            'parent_id' => $uis->id,
        ])->assertRedirect(route('unit-kerja.index'));

        $this->assertDatabaseHas('unit_kerjas', ['id' => $unit->id, 'nama' => 'BAUK Universitas Ibnu Sina']);

        $this->delete(route('unit-kerja.destroy', $unit))
            ->assertRedirect(route('unit-kerja.index'));
    }

    public function test_hak_akses_role_matrix_plotting_and_portal_masuk(): void
    {
        $admin = $this->seedOtkData();

        $this->get(route('hak-akses.index'))
            ->assertOk()
            ->assertSee('Hak Akses &amp; Role (OTK)', false)
            ->assertSee('LaravelDataTables["roles-table"]', false);

        $permIds = Permission::take(3)->pluck('id')->all();

        $this->post(route('hak-akses.store'), [
            'kode' => 'operator_ft',
            'nama' => 'Operator Fakultas Teknik',
            'scope' => 'unit',
            'deskripsi' => 'Operator data kepegawaian FT',
            'permissions' => $permIds,
        ])->assertRedirect(route('hak-akses.index'));

        $role = Role::where('kode', 'operator_ft')->firstOrFail();
        $this->assertCount(3, $role->permissions);

        $modul = Modul::where('kode', 'simpeg')->firstOrFail();
        $unitFt = UnitKerja::where('kode', 'FST')->firstOrFail();
        $jabatan = JenisJabatanStruktural::where('kode', 'TUFT')->firstOrFail();

        $this->post(route('hak-akses.plotting.store'), [
            'user_id' => $admin->id,
            'modul_id' => $modul->id,
            'role_id' => $role->id,
            'unit_kerja_id' => $unitFt->id,
            'jenis_jabatan_struktural_id' => $jabatan->id,
        ])->assertRedirect(route('hak-akses.index', ['tab' => 'plotting']));

        $plot = UserRole::where('user_id', $admin->id)->where('role_id', $role->id)->firstOrFail();

        $this->get(route('modul.masuk', $plot))
            ->assertRedirect(url('/dashboard'));

        $this->assertSame($role->id, session('active_role_id'));
        $this->assertSame($unitFt->id, session('active_unit_kerja_id'));

        // Active role operator_ft does not have hak_akses.view -> 403 Forbidden
        $this->delete(route('hak-akses.plotting.destroy', $plot))->assertForbidden();

        // Switch back to Super Admin session -> allowed
        $superAdminRole = Role::where('kode', 'super_admin')->firstOrFail();
        $this->withSession(['active_role_id' => $superAdminRole->id]);

        $this->delete(route('hak-akses.plotting.destroy', $plot))
            ->assertRedirect(route('hak-akses.index', ['tab' => 'plotting']));

        $this->delete(route('hak-akses.destroy', $role))
            ->assertRedirect(route('hak-akses.index'));
    }

    public function test_staff_is_forbidden_from_admin_routes_and_user_otk_sync_works(): void
    {
        $this->seedOtkData();

        $staffRole = Role::where('kode', 'staff')->firstOrFail();
        $modul = Modul::where('kode', 'simpeg')->firstOrFail();
        $unitFt = UnitKerja::where('kode', 'FST')->firstOrFail();
        $jabatan = JenisJabatanStruktural::where('kode', 'TUFT')->firstOrFail();

        // Create user with OTK role assignment
        $this->post(route('users.store'), [
            'name' => 'Staff Uji',
            'username' => 'staffuji',
            'email' => 'staffuji@uis.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_assignments' => [
                [
                    'modul_id' => $modul->id,
                    'role_id' => $staffRole->id,
                    'unit_kerja_id' => $unitFt->id,
                    'jenis_jabatan_struktural_id' => $jabatan->id,
                ],
            ],
        ])->assertRedirect(route('users.index'));

        $staff = User::where('username', 'staffuji')->firstOrFail();
        $this->assertCount(1, $staff->userRoles);

        // Switch to staff user -> forbidden on admin routes
        $this->actingAs($staff);

        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('hak-akses.index'))->assertForbidden();
        $this->get(route('pengaturan.modul.index'))->assertForbidden();
        $this->get(route('pengaturan.backup.index'))->assertForbidden();
        $this->get(route('unit-kerja.index'))->assertForbidden();

        // Dashboard, Profile, Activity Log remain accessible
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('profile.index'))->assertOk();
        $this->get(route('activity.index'))->assertOk();
    }
}
