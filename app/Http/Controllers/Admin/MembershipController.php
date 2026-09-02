<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Repositories\Contracts\MembershipRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function __construct(private MembershipRepositoryInterface $memberships)
    {
    }

    public function index(Request $request): View
    {
        $memberships = $this->memberships->paginateWithCounts($request->query('q'), 15);

        return view('admin.memberships.index', compact('memberships'));
    }

    public function create(): View
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:60'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Membership::create($data);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil ditambahkan.');
    }

    public function edit(Membership $membership): View
    {
        return view('admin.memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:60'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $membership->update($data);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil diperbarui.');
    }

    public function destroy(Membership $membership): RedirectResponse
    {
        $membership->delete();

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil dihapus.');
    }
}
