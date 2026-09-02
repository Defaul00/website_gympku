<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymEquipment;
use App\Repositories\Contracts\GymEquipmentRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GymEquipmentController extends Controller
{
    public function __construct(private GymEquipmentRepositoryInterface $equipments)
    {
    }

    public function index(Request $request): View
    {
        $equipments = $this->equipments->paginateFiltered($request->query('q'), $request->query('category'), 15);
        $categories = \App\Models\GymEquipment::query()->distinct()->orderBy('category')->pluck('category')->toArray();

        return view('admin.equipments.index', compact('equipments', 'categories'));
    }

    public function create(): View
    {
        return view('admin.equipments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'condition' => ['required', 'in:good,needs_maintenance,poor'],
            'last_maintenance' => ['nullable', 'date'],
            'next_maintenance' => ['nullable', 'date'],
        ]);

        GymEquipment::create($data);

        return redirect()->route('admin.equipments.index')
            ->with('success', 'Peralatan berhasil ditambahkan.');
    }

    public function edit(GymEquipment $equipment): View
    {
        return view('admin.equipments.edit', compact('equipment'));
    }

    public function update(Request $request, GymEquipment $equipment): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'condition' => ['required', 'in:good,needs_maintenance,poor'],
            'last_maintenance' => ['nullable', 'date'],
            'next_maintenance' => ['nullable', 'date'],
        ]);

        $equipment->update($data);

        return redirect()->route('admin.equipments.index')
            ->with('success', 'Peralatan berhasil diperbarui.');
    }

    public function destroy(GymEquipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()->route('admin.equipments.index')
            ->with('success', 'Peralatan berhasil dihapus.');
    }
}
