<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function search(Request $request)
    {
        if ($request->filled('id')) {
            $unit = Unit::find($request->id);

            return response()->json([
                'items' => $unit ? [['id' => $unit->id, 'name' => $unit->name]] : [],
                'has_more' => false,
            ]);
        }

        $query = Unit::orderBy('name');

        if ($request->filled('q')) {
            $query->where('name', 'ilike', '%' . $request->q . '%');
        }

        $units = $query->take(11)->get();
        $hasMore = $units->count() > 10;

        return response()->json([
            'items' => $units->take(10)->map(fn ($u) => ['id' => $u->id, 'name' => $u->name]),
            'has_more' => $hasMore,
        ]);
    }

    public function index()
    {
        $units = Unit::latest()->paginate(15);

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
        ]);

        $unit->update($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}
