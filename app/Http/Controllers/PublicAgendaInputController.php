<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaImage;
use App\Models\AgendaNote;
use Illuminate\Http\Request;

class PublicAgendaInputController extends Controller
{
    public function show(Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);

        $agenda->load(['room', 'notes', 'images']);

        return view('public.agenda-input', compact('agenda'));
    }

    public function storeNote(Request $request, Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);

        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'decision' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $agenda->notes()->create($validated);

        return redirect()->route('agenda.input', $agenda)
            ->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function storeImage(Request $request, Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $path = $request->file('image')->store('agenda-images/' . $agenda->id, 'public');

        $agenda->images()->create([
            'image_path' => $path,
        ]);

        return redirect()->route('agenda.input', $agenda)
            ->with('success', 'Foto berhasil diunggah.');
    }
}
