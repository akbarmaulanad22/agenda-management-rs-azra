<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date',
        'event_time',
        'status',
        'template_id',
        'created_by_signer_id',
        'validated_by_signer_id',
        'letter_place',
        'letter_number',
        'letter_recipient',
        'letter_body',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Signer::class, 'created_by_signer_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(Signer::class, 'validated_by_signer_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'agenda_participant')
            ->withPivot(['signature_path', 'signed_at'])
            ->withTimestamps();
    }
}
