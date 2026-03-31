<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\InvitationTemplate;
use App\Models\Participant;
use App\Models\Signer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveAgendaWithParticipant(): array
    {
        $template = InvitationTemplate::factory()->create();
        $signer1 = Signer::factory()->create();
        $signer2 = Signer::factory()->create();

        $agenda = Agenda::factory()->create([
            'status' => 'active',
            'template_id' => $template->id,
            'created_by_signer_id' => $signer1->id,
            'validated_by_signer_id' => $signer2->id,
        ]);

        $participant = Participant::factory()->create();
        $agenda->participants()->attach($participant->id);

        return [$agenda, $participant];
    }

    public function test_prevents_double_attendance(): void
    {
        [$agenda, $participant] = $this->createActiveAgendaWithParticipant();

        // Sign the first time
        $agenda->participants()->updateExistingPivot($participant->id, [
            'signature_path' => 'signatures/existing.png',
            'signed_at' => now(),
        ]);

        // Attempt to sign again
        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'participant_id' => $participant->id,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUg==',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Anda sudah melakukan absensi.']);
    }

    public function test_allows_first_attendance(): void
    {
        [$agenda, $participant] = $this->createActiveAgendaWithParticipant();

        $pngData = base64_encode(hex2bin(
            '89504e470d0a1a0a0000000d49484452000000010000000108060000001f15c489' .
            '0000000a49444154789c626000000002000198e195290000000049454e44ae426082'
        ));

        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'participant_id' => $participant->id,
            'signature' => 'data:image/png;base64,' . $pngData,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Absensi berhasil disimpan.']);

        $this->assertNotNull(
            $agenda->participants()->where('participant_id', $participant->id)->first()->pivot->signed_at
        );
    }

    public function test_rejects_non_participant(): void
    {
        [$agenda] = $this->createActiveAgendaWithParticipant();
        $outsider = Participant::factory()->create();

        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'participant_id' => $outsider->id,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUg==',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Anda tidak terdaftar dalam agenda ini.']);
    }
}
