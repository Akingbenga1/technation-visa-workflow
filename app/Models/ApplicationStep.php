<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'name',
        'description',
        'instructions',
        'order',
        'form_type',
        'form_schema',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'form_schema' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ApplicationStage::class, 'stage_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class, 'step_id');
    }

    public function userApplications(): HasMany
    {
        return $this->hasMany(UserApplication::class, 'current_step_id');
    }
} 