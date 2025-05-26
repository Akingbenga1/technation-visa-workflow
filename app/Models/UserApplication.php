<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class UserApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_number',
        'status',
        'current_stage_id',
        'current_step_id',
        'submitted_at',
        'reviewed_at',
        'completed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($application) {
            if (empty($application->reference_number)) {
                $application->reference_number = 'GTV-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(ApplicationStage::class, 'current_stage_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(ApplicationStep::class, 'current_step_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }
} 