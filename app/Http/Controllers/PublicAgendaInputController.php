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
        abort_unless($agenda->allowsNotes(), 403);

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
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $count = 0;
        foreach ($request->file('images') as $file) {
            $path = $file->store('agenda-images/' . $agenda->id, 'public');
            $agenda->images()->create(['image_path' => $path]);
            $count++;
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'count' => $count]);
        }

        return redirect()->route('agenda.input', $agenda)
            ->with('success', $count . ' foto berhasil diunggah.');
    }
}
