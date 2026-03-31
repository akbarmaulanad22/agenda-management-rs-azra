<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Signer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'position', 'signature_path'];

    public function createdAgendas(): HasMany
    {
        return $this->hasMany(Agenda::class, 'created_by_signer_id');
    }

    public function validatedAgendas(): HasMany
    {
        return $this->hasMany(Agenda::class, 'validated_by_signer_id');
    }
}
