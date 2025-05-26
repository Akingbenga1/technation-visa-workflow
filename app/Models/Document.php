<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_response_id',
        'name',
        'file_path',
        'mime_type',
        'size',
        'document_type', // e.g., passport, cv, endorsement_letter
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applicationResponse(): BelongsTo
    {
        return $this->belongsTo(ApplicationResponse::class);
    }
} 