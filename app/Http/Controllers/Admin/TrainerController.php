<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\User;
use App\Repositories\Contracts\TrainerRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TrainerController extends Controller
{
    public function __construct(private TrainerRepositoryInterface $trainers)
    {
    }

    public function index(Request $request): View
    {
        $trainers = $this->trainers->paginateWithUser($request->query('q'), 15);

        return view('admin.trainers.index', compact('trainers'));
    }

    public function create(): View
    {
        $users = User::whereHas('role', fn ($q) => $q->where('name', 'trainer'))
            ->doesntHave('trainerProfile')
            ->orderBy('name')
            ->get();

        return view('admin.trainers.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required_without:user_id', 'string', 'max:255'],
            'email' => ['required_without:user_id', 'email', 'unique:users,email'],
            'user_id' => ['nullable', 'exists:users,id'],
            'specialization' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['user_id'])) {
            $user = User::findOrFail($data['user_id']);
        } else {
            $user = User::create([
                'role_id' => Role::where('name', 'trainer')->value('id'),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
            ]);
        }

        Trainer::create([
            'user_id' => $user->id,
            'specialization' => $data['specialization'],
            'bio' => $data['bio'] ?? null,
            'experience_years' => $data['experience_years'],
            'hourly_rate' => $data['hourly_rate'],
            'is_available' => $data['is_available'] ?? true,
        ]);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil ditambahkan.');
    }

    public function edit(Trainer $trainer): View
    {
        $trainer->load('user');

        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer): RedirectResponse
    {
        $data = $request->validate([
            'specialization' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $trainer->update($data);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil diperbarui.');
    }

    public function destroy(Trainer $trainer): RedirectResponse
    {
        $trainer->delete();

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil dihapus.');
    }
}
