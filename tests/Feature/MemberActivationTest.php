<?php

namespace Tests\Feature;

use App\Models\Membership;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberActivationTest extends TestCase
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

    public function test_admin_can_activate_a_member_and_member_card_is_created(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $membership = Membership::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.members.activate', $member), [
            'membership_id' => $membership->id,
            'payment_method' => 'qris',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.members.show', $member));

        $card = $member->memberCards()->first();

        $this->assertNotNull($card);
        $this->assertSame('active', $card->status);
        $this->assertSame($membership->id, $card->membership_id);
        $this->assertEquals(now()->format('Y-m-d'), $card->start_date->format('Y-m-d'));
        $this->assertNotNull($member->activeMemberCard());

        $this->assertSame(1, $member->payments()->count());
        $this->assertSame('qris', $member->payments()->first()->method);
    }

    public function test_admin_can_deactivate_a_member(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $membership = Membership::factory()->create();

        $this->actingAs($admin)->post(route('admin.members.activate', $member), [
            'membership_id' => $membership->id,
            'payment_method' => 'cash',
        ]);

        $this->assertNotNull($member->activeMemberCard());

        $response = $this->actingAs($admin)->post(route('admin.members.deactivate', $member));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.members.show', $member));

        $member->refresh();

        $this->assertSame('expired', $member->memberCards()->first()->status);
        $this->assertNull($member->activeMemberCard());
    }

    public function test_member_cannot_activate_or_deactivate_other_members(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $other = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $membership = Membership::factory()->create();

        $this->actingAs($member)
            ->post(route('admin.members.activate', $other), [
                'membership_id' => $membership->id,
                'payment_method' => 'cash',
            ])
            ->assertForbidden();

        $this->actingAs($member)
            ->post(route('admin.members.deactivate', $other))
            ->assertForbidden();

        $this->assertNull($other->memberCards()->first());
    }

    public function test_inactive_membership_package_cannot_be_used_to_activate(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $membership = Membership::factory()->create(['is_active' => false]);

        $this->actingAs($admin)
            ->post(route('admin.members.activate', $member), [
                'membership_id' => $membership->id,
                'payment_method' => 'cash',
            ])
            ->assertNotFound();

        $this->assertNull($member->memberCards()->first());
    }
}
