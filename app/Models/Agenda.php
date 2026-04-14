<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'status',
        'organizer_id',
        'meeting_chair_id',
        'unit_id',
        'room_id',
        'letter_file_path',
        'material_file_path',
        'type',
        'bank_soal_id',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'organizer_id');
    }

    public function meetingChair(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'meeting_chair_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'agenda_employee')
            ->withPivot('signature_image_path')
            ->withTimestamps();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(AgendaNote::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AgendaImage::class);
    }

    public function agendaQuestions(): HasMany
    {
        return $this->hasMany(AgendaQuestion::class);
    }

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function allowsNotes(): bool
    {
        return $this->type === 'rapat';
    }

    public function allowsQuiz(): bool
    {
        return in_array($this->type, ['diklat', 'pelatihan']);
    }
}
