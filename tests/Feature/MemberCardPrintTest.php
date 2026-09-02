<?php

namespace Tests\Feature;

use App\Models\MemberCard;
use App\Models\Membership;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberCardPrintTest extends TestCase
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

    private function makeCardFor(User $user): MemberCard
    {
        return MemberCard::create([
            'user_id' => $user->id,
            'membership_id' => Membership::factory()->create()->id,
            'card_number' => 'PG-TEST-010101-ABCD',
            'start_date' => today(),
            'end_date' => today()->addMonths(3),
            'status' => 'active',
        ]);
    }

    public function test_member_can_print_their_own_card(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $card = $this->makeCardFor($member);

        $response = $this->actingAs($member)->get(route('member-card.print', $card));

        $response->assertOk();
        $response->assertSee($card->card_number);
        $response->assertSee(strtoupper($member->name));
    }

    public function test_member_cannot_print_another_members_card(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $other = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $card = $this->makeCardFor($other);

        $this->actingAs($member)->get(route('member-card.print', $card))->assertForbidden();
    }

    public function test_admin_can_print_any_members_card(): void
    {
        $admin = User::factory()->withRole(Role::where('name', 'admin')->value('id'))->create();
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $card = $this->makeCardFor($member);

        $this->actingAs($admin)->get(route('member-card.print', $card))->assertOk();
    }

    public function test_guest_cannot_print_card(): void
    {
        $member = User::factory()->withRole(Role::where('name', 'member')->value('id'))->create();
        $card = $this->makeCardFor($member);

        $this->get(route('member-card.print', $card))->assertRedirect(route('login'));
    }
}
