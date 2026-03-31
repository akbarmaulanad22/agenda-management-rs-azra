<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::latest()->paginate(10);
        return view('admin.participants.index', compact('participants'));
    }

    public function create()
    {
        return view('admin.participants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier_number' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        Participant::create($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function edit(Participant $participant)
    {
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier_number' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $participant->update($validated);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Peserta berhasil diperbarui.');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
