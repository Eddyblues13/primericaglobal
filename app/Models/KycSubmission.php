<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'id_document_path',
        'proof_of_address_path',
        'selfie_path',
        'status',
        'rejection_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the KYC submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
