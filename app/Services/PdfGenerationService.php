<?php

namespace App\Services;

use App\Models\Agenda;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerationService
{
    public function generateInvitation(Agenda $agenda): \Barryvdh\DomPDF\PDF
    {
        $agenda->load(['creator', 'validator', 'participants']);

        return Pdf::loadView('pdf.invitation', [
            'agenda' => $agenda,
        ])->setPaper('a4', 'portrait');
    }
}
