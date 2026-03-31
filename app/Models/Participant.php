<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'identifier_number', 'position', 'department'];

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(Agenda::class, 'agenda_participant')
            ->withPivot(['signature_path', 'signed_at'])
            ->withTimestamps();
    }
}
