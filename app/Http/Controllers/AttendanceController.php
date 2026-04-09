<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Employee;
use App\Services\SignatureStorageService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show(Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);

        $agenda->load(['room', 'agendaQuestions']);

        $signedEmployees = $agenda->employees()
            ->whereNotNull('agenda_employee.signature_image_path')
            ->get();

        $signedEmployeeIds = $signedEmployees->pluck('id')->toArray();

        $allEmployees = Employee::with('unit')->orderBy('full_name')->get();

        $employeesJson = $allEmployees->map(function ($e) use ($signedEmployeeIds) {
            return [
                'id' => $e->id,
                'name' => $e->full_name,
                'position' => $e->job_position,
                'organization' => $e->unit->name ?? '-',
                'signed_at' => in_array($e->id, $signedEmployeeIds),
            ];
        });

        $attendeesJson = $signedEmployees->load('unit')->map(function ($e) {
            return [
                'id' => $e->id,
                'name' => $e->full_name,
                'position' => $e->job_position,
                'organization' => $e->unit->name ?? '-',
                'signature_url' => asset('storage/' . $e->pivot->signature_image_path),
                'signed_at' => $e->pivot->created_at?->format('H:i'),
            ];
        });

        return view('attendance.show', compact('agenda', 'employeesJson', 'attendeesJson'));
    }

    public function sign(Request $request, Agenda $agenda, SignatureStorageService $signatureService)
    {
        abort_unless($agenda->status === 'active', 404);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'signature' => 'required|string',
        ]);

        $pivot = $agenda->employees()
            ->where('employee_id', $request->employee_id)
            ->first();

        if ($pivot && $pivot->pivot->signature_image_path) {
            return response()->json([
                'message' => 'Anda sudah melakukan absensi.'
            ], 422);
        }

        $signaturePath = $signatureService->storeBase64($request->signature);

        if ($pivot) {
            $agenda->employees()->updateExistingPivot($request->employee_id, [
                'signature_image_path' => $signaturePath,
            ]);
        } else {
            $agenda->employees()->attach($request->employee_id, [
                'signature_image_path' => $signaturePath,
            ]);
        }

        $employee = Employee::with('unit')->find($request->employee_id);

        return response()->json([
            'message' => 'Absensi berhasil disimpan.',
            'attendee' => [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'position' => $employee->job_position,
                'organization' => $employee->unit->name ?? '-',
                'signature_url' => asset('storage/' . $signaturePath),
                'signed_at' => now()->format('H:i'),
            ],
        ]);
    }
}
