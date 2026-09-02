<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insert([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'trainer', 'display_name' => 'Personal Trainer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'member', 'display_name' => 'Member', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_trainer_cannot_access_admin_dashboard(): void
    {
        $trainer = User::factory()->withRole(Role::where('name', 'trainer')->value('id'))->create();

        $this->actingAs($trainer)->get('/admin')->assertForbidden();
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();

        $this->actingAs($member)->get('/admin')->assertForbidden();
    }

    public function test_member_can_access_user_dashboard(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();

        $this->actingAs($member)->get('/home')->assertOk();
    }

    public function test_admin_cannot_access_user_dashboard(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();

        $this->actingAs($admin)->get('/home')->assertForbidden();
    }

    public function test_trainer_cannot_access_user_dashboard(): void
    {
        $trainer = User::factory()->withRole(Role::where('name', 'trainer')->value('id'))->create();

        $this->actingAs($trainer)->get('/home')->assertForbidden();
    }

    public function test_trainer_can_access_trainer_dashboard(): void
    {
        $trainerUser = User::factory()->withRole(Role::where('name', 'trainer')->value('id'))->create();
        Trainer::factory()->create(['user_id' => $trainerUser->id]);

        $this->actingAs($trainerUser)->get('/trainer')->assertOk();
    }

    public function test_admin_cannot_access_trainer_dashboard(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();

        $this->actingAs($admin)->get('/trainer')->assertForbidden();
    }

    public function test_member_cannot_access_trainer_dashboard(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();

        $this->actingAs($member)->get('/trainer')->assertForbidden();
    }

    public function test_home_route_is_role_specific(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();
        $trainer = User::factory()->withRole(Role::where('name', 'trainer')->value('id'))->create();
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();

        $this->assertSame('admin.dashboard', $admin->homeRoute());
        $this->assertSame('trainer.dashboard', $trainer->homeRoute());
        $this->assertSame('user.dashboard', $member->homeRoute());
    }
}
