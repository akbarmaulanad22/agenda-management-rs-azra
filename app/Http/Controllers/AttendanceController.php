<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Services\SignatureStorageService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show(Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);

        $agenda->load(['participants' => function ($query) {
            $query->orderBy('name');
        }]);

        $participantsJson = $agenda->participants->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'position' => $p->position,
                'department' => $p->department,
                'signed_at' => $p->pivot->signed_at,
            ];
        });

        return view('attendance.show', compact('agenda', 'participantsJson'));
    }

    public function sign(Request $request, Agenda $agenda, SignatureStorageService $signatureService)
    {
        abort_unless($agenda->status === 'active', 404);

        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'signature' => 'required|string',
        ]);

        $pivot = $agenda->participants()
            ->where('participant_id', $request->participant_id)
            ->first();

        if (!$pivot) {
            return response()->json([
                'message' => 'Anda tidak terdaftar dalam agenda ini.'
            ], 422);
        }

        if ($pivot->pivot->signed_at) {
            return response()->json([
                'message' => 'Anda sudah melakukan absensi.'
            ], 422);
        }

        $signaturePath = $signatureService->storeBase64($request->signature);

        $agenda->participants()->updateExistingPivot($request->participant_id, [
            'signature_path' => $signaturePath,
            'signed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Absensi berhasil disimpan.'
        ]);
    }
}
