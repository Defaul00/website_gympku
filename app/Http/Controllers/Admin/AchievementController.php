<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Repositories\Contracts\AchievementRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function __construct(private AchievementRepositoryInterface $achievements)
    {
    }

    public function index(Request $request): View
    {
        $achievements = $this->achievements->paginateWithCounts($request->query('q'), 15);

        return view('admin.achievements.index', compact('achievements'));
    }

    public function create(): View
    {
        return view('admin.achievements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'points' => ['required', 'integer', 'min:0'],
            'badge_color' => ['nullable', 'string', 'max:20'],
        ]);

        Achievement::create($data);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement berhasil ditambahkan.');
    }

    public function edit(Achievement $achievement): View
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'points' => ['required', 'integer', 'min:0'],
            'badge_color' => ['nullable', 'string', 'max:20'],
        ]);

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement berhasil dihapus.');
    }
}
