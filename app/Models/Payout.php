<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'wallet_id',
        'payout_number',
        'amount',
        'status',
        'payout_method',
        'stripe_transfer_id',
        'admin_notes',
        'rejection_reason',
        'processed_at',
        'paid_at',
        'stripe_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'stripe_data' => 'array',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the creator requesting the payout
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the wallet associated with the payout
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Generate unique payout number
     */
    public static function generatePayoutNumber(): string
    {
        $prefix = 'PAY';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$timestamp}{$random}";
    }

    /**
     * Check if payout is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payout is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if payout is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if payout is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payout is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve payout
     */
    public function approve(string $adminNotes = null): void
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $adminNotes,
            'processed_at' => now(),
        ]);
    }

    /**
     * Reject payout
     */
    public function reject(string $reason, string $adminNotes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'admin_notes' => $adminNotes,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark payout as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }
}
