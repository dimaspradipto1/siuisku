<?php

namespace Database\Seeders;

use App\Models\JenisJabatanStruktural;
use App\Models\Modul;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class HakAksesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles berbasis OTK
        $rolesData = [
            [
                'kode' => 'super_admin',
                'nama' => 'Super Admin',
                'scope' => 'universitas',
                'deskripsi' => 'Akses penuh seluruh sistem, pengaturan modul, hak akses, dan backup.',
            ],
            [
                'kode' => 'admin_kepegawaian',
                'nama' => 'Admin Kepegawaian',
                'scope' => 'universitas',
                'deskripsi' => 'Mengelola data pegawai, kehadiran, cuti, validasi, dan referensi kepegawaian.',
            ],
            [
                'kode' => 'pimpinan_universitas',
                'nama' => 'Pimpinan Universitas',
                'scope' => 'universitas',
                'deskripsi' => 'Rektor & Wakil Rektor — monitoring dan persetujuan tingkat universitas.',
            ],
            [
                'kode' => 'pimpinan_fakultas',
                'nama' => 'Pimpinan Fakultas',
                'scope' => 'unit',
                'deskripsi' => 'Dekan & Wakil Dekan — monitoring dan persetujuan pada fakultas & prodi di bawahnya.',
            ],
            [
                'kode' => 'kepala_lembaga',
                'nama' => 'Kepala Lembaga / Biro',
                'scope' => 'unit',
                'deskripsi' => 'Ka. LPTI, Ka. LPPM, Ka. LPMI, Ka. Biro — mengelola & memvalidasi staf unitnya.',
            ],
            [
                'kode' => 'pimpinan_prodi',
                'nama' => 'Pimpinan Prodi',
                'scope' => 'unit',
                'deskripsi' => 'Kaprodi & Sekprodi — monitoring dan persetujuan pada program studi masing-masing.',
            ],
            [
                'kode' => 'staff',
                'nama' => 'Staff',
                'scope' => 'self',
                'deskripsi' => 'Akses mandiri untuk profil, kehadiran, cuti, dan dokumen pribadi.',
            ],
        ];

        $roles = [];
        foreach ($rolesData as $data) {
            $roles[$data['kode']] = Role::updateOrCreate(['kode' => $data['kode']], $data);
        }

        // 2. Seed Permissions Matrix per Menu SIMPEG
        $menuActions = [
            'dashboard' => ['label' => 'Dashboard', 'actions' => ['view']],
            'pegawai' => ['label' => 'Pegawai', 'actions' => ['view', 'create', 'update', 'delete']],
            'kehadiran' => ['label' => 'Kehadiran', 'actions' => ['view', 'create', 'update', 'delete', 'approve']],
            'cuti' => ['label' => 'Cuti & Izin', 'actions' => ['view', 'create', 'update', 'delete', 'approve']],
            'validasi' => ['label' => 'Validasi Data', 'actions' => ['view', 'approve']],
            'referensi' => ['label' => 'Referensi Kepegawaian', 'actions' => ['view', 'create', 'update', 'delete']],
            'modul' => ['label' => 'Pengaturan Modul', 'actions' => ['view', 'create', 'update', 'delete']],
            'users' => ['label' => 'Manajemen Users', 'actions' => ['view', 'create', 'update', 'delete']],
            'hak_akses' => ['label' => 'Hak Akses & Role', 'actions' => ['view', 'create', 'update', 'delete']],
            'backup' => ['label' => 'Backup Database & Storage', 'actions' => ['view', 'create', 'update']],
        ];

        $actionLabels = [
            'view' => 'Lihat',
            'create' => 'Tambah',
            'update' => 'Ubah',
            'delete' => 'Hapus',
            'approve' => 'Validasi / Setujui',
        ];

        $allPermissionIds = [];
        $permMap = [];

        foreach ($menuActions as $menuKey => $meta) {
            foreach ($meta['actions'] as $aksi) {
                $kode = "{$menuKey}.{$aksi}";
                $perm = Permission::updateOrCreate(
                    ['kode' => $kode],
                    [
                        'modul_kode' => 'simpeg',
                        'menu' => $meta['label'],
                        'nama' => "{$actionLabels[$aksi]} {$meta['label']}",
                        'aksi' => $aksi,
                    ]
                );
                $allPermissionIds[] = $perm->id;
                $permMap[$kode] = $perm->id;
            }
        }

        // 3. Assign Default Permissions per Role
        $roles['super_admin']->permissions()->sync($allPermissionIds);

        $roles['admin_kepegawaian']->permissions()->sync(
            collect($permMap)->only([
                'dashboard.view',
                'pegawai.view', 'pegawai.create', 'pegawai.update', 'pegawai.delete',
                'kehadiran.view', 'kehadiran.create', 'kehadiran.update', 'kehadiran.delete', 'kehadiran.approve',
                'cuti.view', 'cuti.create', 'cuti.update', 'cuti.delete', 'cuti.approve',
                'validasi.view', 'validasi.approve',
                'referensi.view', 'referensi.create', 'referensi.update', 'referensi.delete',
                'users.view', 'users.create', 'users.update',
            ])->values()->all()
        );

        $pimpinanPerms = collect($permMap)->only([
            'dashboard.view',
            'pegawai.view',
            'kehadiran.view', 'kehadiran.approve',
            'cuti.view', 'cuti.approve',
            'validasi.view', 'validasi.approve',
        ])->values()->all();

        $roles['pimpinan_universitas']->permissions()->sync($pimpinanPerms);
        $roles['pimpinan_fakultas']->permissions()->sync($pimpinanPerms);
        $roles['kepala_lembaga']->permissions()->sync($pimpinanPerms);
        $roles['pimpinan_prodi']->permissions()->sync($pimpinanPerms);

        $roles['staff']->permissions()->sync(
            collect($permMap)->only([
                'dashboard.view',
                'kehadiran.view', 'kehadiran.create',
                'cuti.view', 'cuti.create',
            ])->values()->all()
        );

        // 4. Plotting default user_roles untuk user yang sudah ada pada modul SIMPEG
        $simpeg = Modul::where('kode', 'simpeg')->first();
        if (! $simpeg) {
            return;
        }

        $unitUis = UnitKerja::where('kode', 'UIS')->first();
        $unitLpti = UnitKerja::where('kode', 'LPTI')->first();
        $unitFt = UnitKerja::where('kode', 'FT')->first();

        $jabatanKlpti = JenisJabatanStruktural::where('kode', 'KLPTI')->first();
        $jabatanTuft = JenisJabatanStruktural::where('kode', 'TUFT')->first();

        $defaultPlots = [
            'superadmin' => ['role' => 'super_admin', 'unit' => $unitUis?->id, 'jabatan' => null],
            'admin' => ['role' => 'admin_kepegawaian', 'unit' => $unitUis?->id, 'jabatan' => null],
            'ropianto' => ['role' => 'kepala_lembaga', 'unit' => $unitLpti?->id, 'jabatan' => $jabatanKlpti?->id],
            'dimas' => ['role' => 'staff', 'unit' => $unitFt?->id, 'jabatan' => $jabatanTuft?->id],
            'auliya' => ['role' => 'staff', 'unit' => $unitUis?->id, 'jabatan' => null],
        ];

        foreach ($defaultPlots as $username => $plot) {
            $user = User::where('username', $username)->first();
            if (! $user) {
                continue;
            }

            UserRole::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'modul_id' => $simpeg->id,
                    'role_id' => $roles[$plot['role']]->id,
                ],
                [
                    'unit_kerja_id' => $plot['unit'],
                    'jenis_jabatan_struktural_id' => $plot['jabatan'],
                ]
            );
        }
    }
}
