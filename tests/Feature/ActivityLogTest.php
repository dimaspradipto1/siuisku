<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_own_activity_logs_and_billing_is_removed(): void
    {
        $userA = User::create([
            'name' => 'User A',
            'username' => 'usera',
            'email' => 'usera@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $userB = User::create([
            'name' => 'User B',
            'username' => 'userb',
            'email' => 'userb@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        ActivityLog::record($userA, 'auth', 'Login berhasil User A', 'Masuk dari Chrome', 'accent');
        ActivityLog::record($userB, 'security', 'Aktivitas Rahasia User B', 'Tidak boleh terlihat oleh User A', 'danger');

        $this->actingAs($userA);

        $response = $this->get(route('activity.index'));

        $response->assertOk();
        $response->assertSee('Login berhasil User A');
        $response->assertDontSee('Aktivitas Rahasia User B');
        $response->assertDontSee('Billing');
    }

    public function test_login_and_export_activity_log_work(): void
    {
        $user = User::create([
            'name' => 'Dimas Pradipto',
            'username' => 'dimas',
            'email' => 'dimas@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        $this->post(route('loginproses'), [
            'login' => 'dimas',
            'password' => 'password123',
        ])->assertRedirect(route('modul'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'category' => 'auth',
            'title' => 'Login berhasil',
        ]);

        $exportResponse = $this->get(route('activity.export'));
        $exportResponse->assertOk();
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
