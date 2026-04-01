<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Employee;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveAgendaWithEmployee(): array
    {
        $room = Room::create(['room_name' => 'Test Room']);

        $agenda = Agenda::create([
            'title' => 'Test Agenda',
            'event_date' => now()->toDateString(),
            'event_time' => '10:00',
            'status' => 'active',
            'organizer' => 'Test Organizer',
            'meeting_chair' => 'Test Chair',
            'room_id' => $room->id,
        ]);

        $employee = Employee::create([
            'nip' => '123456789012345678',
            'full_name' => 'Test Employee',
            'organization' => 'RS AZRA',
            'job_position' => 'Dokter',
            'structural_role' => 'Staf',
            'profession' => 'Tenaga Medis',
        ]);

        $agenda->employees()->attach($employee->id);

        return [$agenda, $employee];
    }

    public function test_prevents_double_attendance(): void
    {
        [$agenda, $employee] = $this->createActiveAgendaWithEmployee();

        $agenda->employees()->updateExistingPivot($employee->id, [
            'signature_image_path' => 'signatures/existing.png',
        ]);

        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'employee_id' => $employee->id,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUg==',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Anda sudah melakukan absensi.']);
    }

    public function test_allows_first_attendance(): void
    {
        [$agenda, $employee] = $this->createActiveAgendaWithEmployee();

        $pngData = base64_encode(hex2bin(
            '89504e470d0a1a0a0000000d49484452000000010000000108060000001f15c489' .
            '0000000a49444154789c626000000002000198e195290000000049454e44ae426082'
        ));

        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'employee_id' => $employee->id,
            'signature' => 'data:image/png;base64,' . $pngData,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Absensi berhasil disimpan.']);

        $this->assertNotNull(
            $agenda->employees()->where('employee_id', $employee->id)->first()->pivot->signature_image_path
        );
    }

    public function test_rejects_non_employee(): void
    {
        [$agenda] = $this->createActiveAgendaWithEmployee();

        $outsider = Employee::create([
            'nip' => '999999999999999999',
            'full_name' => 'Outsider',
            'organization' => 'Other',
            'job_position' => 'Other',
            'structural_role' => 'Other',
            'profession' => 'Other',
        ]);

        $response = $this->postJson("/absen/{$agenda->id}/sign", [
            'employee_id' => $outsider->id,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUg==',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Anda tidak terdaftar dalam agenda ini.']);
    }
}
