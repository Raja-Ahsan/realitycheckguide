<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'creator_id',
        'order_number',
        'amount',
        'commission_rate',
        'commission_amount',
        'creator_earning',
        'stripe_payment_intent_id',
        'status',
        'paid_at',
        'stripe_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'creator_earning' => 'decimal:2',
        'stripe_data' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user who made the purchase
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that was purchased
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Get the creator of the video
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all downloads for this order
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(VideoDownload::class, 'video_id', 'video_id');
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$timestamp}{$random}";
    }

    /**
     * Calculate commission and creator earning
     */
    public static function calculateEarnings(float $amount, float $commissionRate): array
    {
        $commissionAmount = $amount * ($commissionRate / 100);
        $creatorEarning = $amount - $commissionAmount;
        
        return [
            'commission_amount' => round($commissionAmount, 2),
            'creator_earning' => round($creatorEarning, 2),
        ];
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }
}
