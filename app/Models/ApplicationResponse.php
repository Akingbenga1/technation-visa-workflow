<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_application_id',
        'step_id',
        'response_data',
        'is_completed',
    ];

    protected $casts = [
        'response_data' => 'array',
        'is_completed' => 'boolean',
    ];

    public function userApplication(): BelongsTo
    {
        return $this->belongsTo(UserApplication::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(ApplicationStep::class, 'step_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
} 