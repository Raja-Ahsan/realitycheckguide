<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_paid_out',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_paid_out' => 'decimal:2',
    ];

    /**
     * Get the creator that owns the wallet
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all wallet transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get all payout requests
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Check if wallet has sufficient balance for payout
     */
    public function canRequestPayout(float $amount): bool
    {
        return $this->balance >= $amount && $amount > 0;
    }

    /**
     * Get available balance for payout (excluding pending)
     */
    public function getAvailableBalanceAttribute(): float
    {
        return $this->balance - $this->pending_balance;
    }

    /**
     * Add credit to wallet
     */
    public function addCredit(float $amount, string $description, array $metadata = []): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->total_earned += $amount;
        $this->save();

        return $this->transactions()->create([
            'creator_id' => $this->creator_id,
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Deduct from wallet (for payouts)
     */
    public function deduct(float $amount, string $description, array $metadata = []): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->total_paid_out += $amount;
        $this->save();

        return $this->transactions()->create([
            'creator_id' => $this->creator_id,
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Reserve amount for pending payout
     */
    public function reserveForPayout(float $amount): void
    {
        $this->pending_balance += $amount;
        $this->save();
    }

    /**
     * Release reserved amount (if payout is rejected)
     */
    public function releaseReservedAmount(float $amount): void
    {
        $this->pending_balance = max(0, $this->pending_balance - $amount);
        $this->save();
    }
}
