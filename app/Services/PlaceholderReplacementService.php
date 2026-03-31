<?php

namespace App\Services;

use App\Models\Agenda;
use Carbon\Carbon;

class PlaceholderReplacementService
{
    public function replace(string $templateBody, Agenda $agenda): string
    {
        $map = [
            '[JUDUL_AGENDA]' => $agenda->title,
            '[TANGGAL]' => $agenda->event_date->translatedFormat('l, d F Y'),
            '[TEMPAT]' => $agenda->location,
            '[WAKTU]' => Carbon::parse($agenda->event_time)->format('H:i') . ' WIB',
        ];

        return str_replace(array_keys($map), array_values($map), $templateBody);
    }
}
