<?php

namespace Database\Seeders;

use App\Models\InvitationTemplate;
use App\Models\Participant;
use App\Models\Signer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@dassa.test',
            'password' => bcrypt('password'),
        ]);

        Signer::create([
            'name' => 'Dr. Ahmad Sudrajat',
            'position' => 'Kepala Bagian Umum',
        ]);

        Signer::create([
            'name' => 'Ir. Budi Santoso, M.T.',
            'position' => 'Direktur',
        ]);

        Participant::factory(10)->create();

        InvitationTemplate::create([
            'name' => 'Template Undangan Rapat Resmi',
            'body_content' => '<p>Dengan hormat,</p><p>Mengharap kehadiran Bapak/Ibu pada:</p><p><strong>Agenda:</strong> [JUDUL_AGENDA]</p><p><strong>Hari/Tanggal:</strong> [TANGGAL]</p><p><strong>Waktu:</strong> [WAKTU]</p><p><strong>Tempat:</strong> [TEMPAT]</p><p>Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>',
        ]);
    }
}
