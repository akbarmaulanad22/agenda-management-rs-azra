<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Services\PlaceholderReplacementService;
use Carbon\Carbon;
use Tests\TestCase;

class PlaceholderReplacementServiceTest extends TestCase
{
    public function test_replaces_all_placeholders_in_template_body(): void
    {
        $service = new PlaceholderReplacementService();

        $agenda = new Agenda();
        $agenda->title = 'Rapat Koordinasi';
        $agenda->location = 'Ruang Rapat Utama';
        $agenda->event_date = Carbon::parse('2026-04-15');
        $agenda->event_time = '09:00';

        $template = 'Agenda: [JUDUL_AGENDA] pada [TANGGAL] di [TEMPAT] pukul [WAKTU]';
        $result = $service->replace($template, $agenda);

        $this->assertStringContainsString('Rapat Koordinasi', $result);
        $this->assertStringContainsString('Ruang Rapat Utama', $result);
        $this->assertStringContainsString('09:00 WIB', $result);
        $this->assertStringNotContainsString('[JUDUL_AGENDA]', $result);
        $this->assertStringNotContainsString('[TANGGAL]', $result);
        $this->assertStringNotContainsString('[TEMPAT]', $result);
        $this->assertStringNotContainsString('[WAKTU]', $result);
    }
}
