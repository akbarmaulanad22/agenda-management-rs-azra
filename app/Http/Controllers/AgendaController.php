<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaQuestionAnswer;
use App\Models\BankSoal;
use App\Models\Question;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with("room")->latest()->paginate(10);

        return view("admin.agendas.index", compact("agendas"));
    }

    public function create()
    {
        $rooms = Room::all();
        $bankSoals = BankSoal::all();

        return view("admin.agendas.create", compact("rooms", "bankSoals"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "event_date" => "required|date",
            "event_time" => "required",
            "status" => "required|in:draft,active,completed",
            "organizer" => "required|string|max:255",
            "unit" => "nullable|string|max:255",
            "meeting_chair" => "required|string|max:255",
            "room_id" => "required|exists:rooms,id",
            "type" => "required|in:diklat,pelatihan,rapat",
            "bank_soal_id" => "nullable|required_if:type,diklat|required_if:type,pelatihan|exists:bank_soals,id",
            "letter_file" => "nullable|file|mimes:pdf|max:5012",
            "material_file" => "nullable|file|mimes:pdf|max:10240",
        ]);

        $agendaData = collect($validated)
            ->except(["letter_file", "material_file"])
            ->toArray();

        if ($agendaData['type'] === 'rapat') {
            $agendaData['bank_soal_id'] = null;
        }

        $agenda = Agenda::create($agendaData);

        if ($agenda->bank_soal_id) {
            $questions = Question::where('bank_soal_id', $agenda->bank_soal_id)->get();
            $agenda->agendaQuestions()->createMany(
                $questions->map->only(['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option'])->toArray()
            );
        }

        if ($request->hasFile("letter_file")) {
            $agenda->update([
                "letter_file_path" => $request
                    ->file("letter_file")
                    ->store("agenda-files/" . $agenda->id, "public"),
            ]);
        }

        if ($request->hasFile("material_file")) {
            $agenda->update([
                "material_file_path" => $request
                    ->file("material_file")
                    ->store("agenda-files/" . $agenda->id, "public"),
            ]);
        }

        return redirect()
            ->route("admin.agendas.index")
            ->with("success", "Agenda berhasil dibuat.");
    }

    public function show(Agenda $agenda)
    {
        $agenda->load(["room", "employees", "notes", "images", "agendaQuestions", "bankSoal"]);

        $quizResults = collect();
        if ($agenda->agendaQuestions->count() > 0) {
            $totalQuestions = $agenda->agendaQuestions->count();
            $quizResults = AgendaQuestionAnswer::where('agenda_id', $agenda->id)
                ->select('employee_id')
                ->selectRaw('SUM(is_correct) as correct_count')
                ->selectRaw('COUNT(*) as answered_count')
                ->selectRaw('MIN(created_at) as answered_at')
                ->groupBy('employee_id')
                ->with('employee')
                ->get()
                ->map(function ($row) use ($totalQuestions) {
                    return [
                        'employee' => $row->employee,
                        'correct' => (int) $row->correct_count,
                        'total' => $totalQuestions,
                        'score' => $totalQuestions > 0 ? round(($row->correct_count / $totalQuestions) * 100) : 0,
                        'answered_at' => $row->answered_at,
                    ];
                })
                ->sortByDesc('score')
                ->values();
        }

        return view("admin.agendas.show", compact("agenda", "quizResults"));
    }

    public function edit(Agenda $agenda)
    {
        $rooms = Room::all();
        $bankSoals = BankSoal::all();

        return view("admin.agendas.edit", compact("agenda", "rooms", "bankSoals"));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "event_date" => "required|date",
            "event_time" => "required",
            "status" => "required|in:draft,active,completed",
            "organizer" => "required|string|max:255",
            "unit" => "nullable|string|max:255",
            "meeting_chair" => "required|string|max:255",
            "room_id" => "required|exists:rooms,id",
            "type" => "required|in:diklat,pelatihan,rapat",
            "bank_soal_id" => "nullable|required_if:type,diklat|required_if:type,pelatihan|exists:bank_soals,id",
            "letter_file" => "nullable|file|mimes:pdf|max:10240",
            "material_file" => "nullable|file|mimes:pdf|max:10240",
        ]);

        $agendaData = collect($validated)
            ->except(["letter_file", "material_file"])
            ->toArray();

        if ($agendaData['type'] === 'rapat') {
            $agendaData['bank_soal_id'] = null;
        }

        if ($request->hasFile("letter_file")) {
            if ($agenda->letter_file_path) {
                Storage::disk("public")->delete($agenda->letter_file_path);
            }
            $agendaData["letter_file_path"] = $request
                ->file("letter_file")
                ->store("agenda-files/" . $agenda->id, "public");
        }

        if ($request->hasFile("material_file")) {
            if ($agenda->material_file_path) {
                Storage::disk("public")->delete($agenda->material_file_path);
            }
            $agendaData["material_file_path"] = $request
                ->file("material_file")
                ->store("agenda-files/" . $agenda->id, "public");
        }

        $agenda->update($agendaData);

        // Sync snapshot questions
        if ($agenda->type === 'rapat') {
            $agenda->agendaQuestions()->delete();
        } elseif ($agenda->bank_soal_id) {
            $agenda->agendaQuestions()->delete();
            $questions = Question::where('bank_soal_id', $agenda->bank_soal_id)->get();
            $agenda->agendaQuestions()->createMany(
                $questions->map->only(['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option'])->toArray()
            );
        }

        return redirect()
            ->route("admin.agendas.index")
            ->with("success", "Agenda berhasil diperbarui.");
    }

    public function exportPdf(Agenda $agenda)
    {
        $agenda->load(["room", "employees", "notes", "images"]);

        // Convert signature images to base64 for embedding in PDF
        $signatureImages = [];
        foreach ($agenda->employees as $employee) {
            if ($employee->pivot->signature_image_path) {
                $path = Storage::disk("public")->path(
                    $employee->pivot->signature_image_path,
                );
                if (file_exists($path)) {
                    $signatureImages[$employee->id] =
                        "data:image/png;base64," .
                        base64_encode(file_get_contents($path));
                }
            }
        }

        // Convert agenda images to base64 for embedding in PDF
        $agendaImages = [];
        foreach ($agenda->images as $image) {
            $path = Storage::disk("public")->path($image->image_path);
            if (file_exists($path)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $agendaImages[$image->id] =
                    "data:image/" .
                    $ext .
                    ";base64," .
                    base64_encode(file_get_contents($path));
            }
        }

        // Generate separate PDFs for each content section
        $contentSections = [
            'attendance' => 'Daftar Kehadiran',
            'notes'      => 'Notulensi Rapat',
            'photos'     => 'Dokumentasi Foto',
        ];

        $tempPaths = [];
        foreach ($contentSections as $sectionKey => $sectionLabel) {
            $section = $sectionKey;
            $sectionPdf = Pdf::loadView(
                "admin.agendas.export-pdf",
                compact("agenda", "signatureImages", "agendaImages", "section"),
            )->setPaper("a4", "portrait");

            $tmpPath = tempnam(sys_get_temp_dir(), "agenda_{$sectionKey}_") . ".pdf";
            file_put_contents($tmpPath, $sectionPdf->output());
            $tempPaths[] = $tmpPath;
        }

        // Collect PDF files to merge in order:
        // Surat Undangan → Materi → Daftar Kehadiran → Notulensi → Dokumentasi
        $pdfFiles = [];

        if ($agenda->letter_file_path) {
            $letterPath = Storage::disk("public")->path(
                $agenda->letter_file_path,
            );
            if (
                file_exists($letterPath) &&
                strtolower(pathinfo($letterPath, PATHINFO_EXTENSION)) === "pdf"
            ) {
                $pdfFiles[] = ['path' => $letterPath, 'type' => 'letter'];
            }
        }

        if ($agenda->material_file_path) {
            $materialPath = Storage::disk("public")->path(
                $agenda->material_file_path,
            );
            if (
                file_exists($materialPath) &&
                strtolower(pathinfo($materialPath, PATHINFO_EXTENSION)) ===
                    "pdf"
            ) {
                $pdfFiles[] = ['path' => $materialPath, 'type' => 'material'];
            }
        }

        // Add content section PDFs
        $sectionKeys = array_keys($contentSections);
        foreach ($sectionKeys as $idx => $sectionKey) {
            $pdfFiles[] = ['path' => $tempPaths[$idx], 'type' => $sectionKey];
        }

        // Prepare header info
        $headerTitle = $agenda->title;
        $headerDate = $agenda->event_date->translatedFormat('l, d F Y');
        $headerTime = $agenda->event_time . ' WIB';
        $headerRoom = $agenda->room->room_name ?? '-';
        $headerOrganizer = $agenda->organizer;
        $headerUnit = $agenda->unit ?? '-';
        $headerChair = $agenda->meeting_chair;

        // Sub-header labels per type
        $subHeaders = [
            'letter'     => 'Surat Undangan',
            'material'   => 'Materi Rapat',
            'attendance' => 'Daftar Kehadiran',
            'notes'      => 'Notulensi Rapat',
            'photos'     => 'Dokumentasi Foto',
        ];

        // Merge all PDFs using FPDI — every page gets the same header
        $merger = new \setasign\Fpdi\Fpdi();
        $merger->SetAutoPageBreak(false);

        foreach ($pdfFiles as $fileInfo) {
            $pageCount = $merger->setSourceFile($fileInfo['path']);
            for ($p = 1; $p <= $pageCount; $p++) {
                $tpl = $merger->importPage($p);
                $size = $merger->getTemplateSize($tpl);

                $headerHeight = 36; // mm reserved for header + sub-header
                $pageWidth = $size['width'];
                $pageHeight = $size['height'];

                $merger->AddPage($size["orientation"], [
                    $pageWidth,
                    $pageHeight,
                ]);

                // -- Draw header --
                // Title
                $merger->SetFont('Helvetica', 'B', 10);
                $merger->SetTextColor(0, 0, 0);
                $merger->SetXY(12, 5);
                $merger->Cell($pageWidth - 24, 5, $headerTitle, 0, 1, 'L');

                // Info rows
                $merger->SetTextColor(80, 80, 80);
                $colWidth = ($pageWidth - 24) / 2;

                // Row 1: Tanggal + Penyelenggara
                $merger->SetXY(12, 12);
                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(22, 3.5, 'Tanggal', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 26, 3.5, $headerDate, 0, 0);

                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(26, 3.5, 'Penyelenggara', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 30, 3.5, $headerOrganizer, 0, 1);

                // Row 2: Waktu + Unit
                $merger->SetXY(12, 16);
                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(22, 3.5, 'Waktu', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 26, 3.5, $headerTime, 0, 0);

                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(26, 3.5, 'Unit', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 30, 3.5, $headerUnit, 0, 1);

                // Row 3: Ruangan + Pimpinan Rapat
                $merger->SetXY(12, 20);
                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(22, 3.5, 'Ruangan', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 26, 3.5, $headerRoom, 0, 0);

                $merger->SetFont('Helvetica', 'B', 7.5);
                $merger->Cell(26, 3.5, 'Pimpinan Rapat', 0, 0);
                $merger->Cell(4, 3.5, ':', 0, 0);
                $merger->SetFont('Helvetica', '', 7.5);
                $merger->Cell($colWidth - 30, 3.5, $headerChair, 0, 1);

                // Separator line
                $merger->SetDrawColor(30, 30, 30);
                $merger->SetLineWidth(0.4);
                $merger->Line(12, 27, $pageWidth - 12, 27);

                // -- Sub-header (section label) --
                $subLabel = $subHeaders[$fileInfo['type']] ?? '';
                $merger->SetFont('Helvetica', 'B', 11);
                $merger->SetTextColor(0, 0, 0);
                $merger->SetXY(12, 29);
                $merger->Cell($pageWidth - 24, 5, $subLabel, 0, 1, 'L');

                // -- Place imported page below header + sub-header, scaled to fit --
                $availableHeight = $pageHeight - $headerHeight;
                $scale = min($pageWidth / $size['width'], $availableHeight / $size['height']);
                $scaledWidth = $size['width'] * $scale;
                $scaledHeight = $size['height'] * $scale;
                $xOffset = ($pageWidth - $scaledWidth) / 2;
                $merger->useTemplate($tpl, $xOffset, $headerHeight, $scaledWidth, $scaledHeight);
            }
        }

        $filename = "Agenda - " . $agenda->title . ".pdf";

        // Clean up temp files
        foreach ($tempPaths as $tmp) {
            @unlink($tmp);
        }

        return response($merger->Output("S", $filename), 200, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->letter_file_path) {
            Storage::disk("public")->delete($agenda->letter_file_path);
        }
        if ($agenda->material_file_path) {
            Storage::disk("public")->delete($agenda->material_file_path);
        }

        // Clean up agenda images from disk
        foreach ($agenda->images as $image) {
            Storage::disk("public")->delete($image->image_path);
        }

        // Clean up signatures from disk
        foreach ($agenda->employees as $employee) {
            if ($employee->pivot->signature_image_path) {
                Storage::disk("public")->delete(
                    $employee->pivot->signature_image_path,
                );
            }
        }

        $agenda->delete();

        return redirect()
            ->route("admin.agendas.index")
            ->with("success", "Agenda berhasil dihapus.");
    }
}
