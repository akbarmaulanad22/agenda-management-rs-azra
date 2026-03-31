<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Participant;
use App\Models\Signer;
use App\Services\PdfGenerationService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with(['creator', 'validator'])
            ->latest()
            ->paginate(10);

        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        $signers = Signer::all();
        $participants = Participant::all();

        return view('admin.agendas.create', compact('signers', 'participants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'status' => 'required|in:draft,active,completed',
            'created_by_signer_id' => 'required|exists:signers,id',
            'validated_by_signer_id' => 'required|exists:signers,id|different:created_by_signer_id',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:participants,id',
            'letter_place' => 'nullable|string|max:255',
            'letter_number' => 'nullable|string|max:255',
            'letter_recipient' => 'nullable|string',
            'letter_body' => 'nullable|string',
        ]);

        $agenda = Agenda::create(collect($validated)->except('participants')->toArray());
        $agenda->participants()->sync($validated['participants']);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dibuat.');
    }

    public function show(Agenda $agenda)
    {
        $agenda->load(['creator', 'validator', 'participants']);

        return view('admin.agendas.show', compact('agenda'));
    }

    public function edit(Agenda $agenda)
    {
        $agenda->load('participants');
        $signers = Signer::all();
        $participants = Participant::all();

        return view('admin.agendas.edit', compact('agenda', 'signers', 'participants'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'status' => 'required|in:draft,active,completed',
            'created_by_signer_id' => 'required|exists:signers,id',
            'validated_by_signer_id' => 'required|exists:signers,id|different:created_by_signer_id',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:participants,id',
            'letter_place' => 'nullable|string|max:255',
            'letter_number' => 'nullable|string|max:255',
            'letter_recipient' => 'nullable|string',
            'letter_body' => 'nullable|string',
        ]);

        $agenda->update(collect($validated)->except('participants')->toArray());
        $agenda->participants()->sync($validated['participants']);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }

    public function generatePdf(Agenda $agenda, PdfGenerationService $pdfService)
    {
        $pdf = $pdfService->generateInvitation($agenda);

        return $pdf->stream('undangan-' . $agenda->id . '.pdf');
    }
}
