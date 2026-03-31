<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'body_content'];

    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class, 'template_id');
    }
}
