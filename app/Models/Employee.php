<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'full_name',
        'organization',
        'job_position',
        'structural_role',
        'profession',
    ];

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(Agenda::class, 'agenda_employee')
            ->withPivot('signature_image_path')
            ->withTimestamps();
    }
}
