<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(ApplicationStep::class, 'stage_id')->orderBy('order');
    }

    public function userApplications(): HasMany
    {
        return $this->hasMany(UserApplication::class, 'current_stage_id');
    }
} 