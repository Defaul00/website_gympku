<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\MembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $users,
        private MembershipService $membershipService,
    ) {
    }

    public function index(Request $request): View
    {
        $members = $this->users->paginateMembers($request->query('q'), 15);

        return view('admin.members.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['role_id'] = Role::where('name', 'member')->value('id');
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function show(User $member): View
    {
        $member->load('memberCards.membership', 'payments', 'attendances', 'achievements');

        $memberships = Membership::where('is_active', true)->orderBy('name')->get();

        return view('admin.members.show', compact('member', 'memberships'));
    }

    public function activate(Request $request, User $member): RedirectResponse
    {
        $data = $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
            'payment_method' => ['required', 'string', 'in:transfer,qris,cash,card'],
        ]);

        $membership = Membership::where('id', $data['membership_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $this->membershipService->activate($member, $membership, $data['payment_method']);

        return redirect()->route('admin.members.show', $member)
            ->with('success', "Membership {$member->name} berhasil diaktifkan.");
    }

    public function deactivate(User $member): RedirectResponse
    {
        $this->membershipService->deactivate($member);

        return redirect()->route('admin.members.show', $member)
            ->with('success', "Membership {$member->name} berhasil dinonaktifkan.");
    }

    public function edit(User $member): View
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
        ]);

        $member->update($data);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(User $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus.');
    }
}
