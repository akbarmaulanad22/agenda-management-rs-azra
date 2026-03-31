<?php

namespace Database\Factories;

use App\Models\InvitationTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvitationTemplate>
 */
class InvitationTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Template Undangan Rapat',
            'body_content' => '<p>Dengan hormat,</p><p>Mengharap kehadiran Bapak/Ibu pada:</p><p><strong>Agenda:</strong> [JUDUL_AGENDA]</p><p><strong>Hari/Tanggal:</strong> [TANGGAL]</p><p><strong>Waktu:</strong> [WAKTU]</p><p><strong>Tempat:</strong> [TEMPAT]</p><p>Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>',
        ];
    }
}
