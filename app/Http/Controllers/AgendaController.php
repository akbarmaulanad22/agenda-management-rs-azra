<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with('room')
            ->latest()
            ->paginate(10);

        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        $rooms = Room::all();

        return view('admin.agendas.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'status' => 'required|in:draft,active,completed',
            'organizer' => 'required|string|max:255',
            'meeting_chair' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'letter_file' => 'nullable|file|mimes:pdf|max:10240',
            'material_file' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
        ]);

        $agendaData = collect($validated)->except(['letter_file', 'material_file'])->toArray();

        $agenda = Agenda::create($agendaData);

        if ($request->hasFile('letter_file')) {
            $agenda->update([
                'letter_file_path' => $request->file('letter_file')->store('agenda-files/' . $agenda->id, 'public'),
            ]);
        }

        if ($request->hasFile('material_file')) {
            $agenda->update([
                'material_file_path' => $request->file('material_file')->store('agenda-files/' . $agenda->id, 'public'),
            ]);
        }

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dibuat.');
    }

    public function show(Agenda $agenda)
    {
        $agenda->load(['room', 'employees', 'notes', 'images']);

        return view('admin.agendas.show', compact('agenda'));
    }

    public function edit(Agenda $agenda)
    {
        $rooms = Room::all();

        return view('admin.agendas.edit', compact('agenda', 'rooms'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'status' => 'required|in:draft,active,completed',
            'organizer' => 'required|string|max:255',
            'meeting_chair' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'letter_file' => 'nullable|file|mimes:pdf|max:10240',
            'material_file' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
        ]);

        $agendaData = collect($validated)->except(['letter_file', 'material_file'])->toArray();

        if ($request->hasFile('letter_file')) {
            if ($agenda->letter_file_path) {
                Storage::disk('public')->delete($agenda->letter_file_path);
            }
            $agendaData['letter_file_path'] = $request->file('letter_file')->store('agenda-files/' . $agenda->id, 'public');
        }

        if ($request->hasFile('material_file')) {
            if ($agenda->material_file_path) {
                Storage::disk('public')->delete($agenda->material_file_path);
            }
            $agendaData['material_file_path'] = $request->file('material_file')->store('agenda-files/' . $agenda->id, 'public');
        }

        $agenda->update($agendaData);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function exportPdf(Agenda $agenda)
    {
        $agenda->load(['room', 'employees', 'notes', 'images']);

        // Convert signature images to base64 for embedding in PDF
        $signatureImages = [];
        foreach ($agenda->employees as $employee) {
            if ($employee->pivot->signature_image_path) {
                $path = Storage::disk('public')->path($employee->pivot->signature_image_path);
                if (file_exists($path)) {
                    $signatureImages[$employee->id] = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                }
            }
        }

        // Convert agenda images to base64 for embedding in PDF
        $agendaImages = [];
        foreach ($agenda->images as $image) {
            $path = Storage::disk('public')->path($image->image_path);
            if (file_exists($path)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $agendaImages[$image->id] = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        // Generate content PDF (attendees, notes, photos) via dompdf
        $contentPdf = Pdf::loadView('admin.agendas.export-pdf', compact('agenda', 'signatureImages', 'agendaImages'))
            ->setPaper('a4', 'portrait');

        $contentTmpPath = tempnam(sys_get_temp_dir(), 'agenda_content_') . '.pdf';
        file_put_contents($contentTmpPath, $contentPdf->output());

        // Collect PDF files to merge: surat undangan → materi → generated content
        $pdfFiles = [];

        if ($agenda->letter_file_path) {
            $letterPath = Storage::disk('public')->path($agenda->letter_file_path);
            if (file_exists($letterPath) && strtolower(pathinfo($letterPath, PATHINFO_EXTENSION)) === 'pdf') {
                $pdfFiles[] = $letterPath;
            }
        }

        // Only merge material if it's a PDF file
        if ($agenda->material_file_path) {
            $materialPath = Storage::disk('public')->path($agenda->material_file_path);
            if (file_exists($materialPath) && strtolower(pathinfo($materialPath, PATHINFO_EXTENSION)) === 'pdf') {
                $pdfFiles[] = $materialPath;
            }
        }

        $pdfFiles[] = $contentTmpPath;

        // Merge all PDFs using FPDI
        $merger = new \setasign\Fpdi\Fpdi();

        foreach ($pdfFiles as $file) {
            $pageCount = $merger->setSourceFile($file);
            for ($p = 1; $p <= $pageCount; $p++) {
                $tpl = $merger->importPage($p);
                $size = $merger->getTemplateSize($tpl);
                $merger->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $merger->useTemplate($tpl);
            }
        }

        $filename = 'Agenda - ' . $agenda->title . '.pdf';

        // Clean up temp file
        @unlink($contentTmpPath);

        return response($merger->Output('S', $filename), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->letter_file_path) {
            Storage::disk('public')->delete($agenda->letter_file_path);
        }
        if ($agenda->material_file_path) {
            Storage::disk('public')->delete($agenda->material_file_path);
        }

        // Clean up agenda images from disk
        foreach ($agenda->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Clean up signatures from disk
        foreach ($agenda->employees as $employee) {
            if ($employee->pivot->signature_image_path) {
                Storage::disk('public')->delete($employee->pivot->signature_image_path);
            }
        }

        $agenda->delete();

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
