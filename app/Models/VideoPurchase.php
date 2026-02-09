<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user who made the purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that was purchased
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Check if the purchase is still valid
     */
    public function isValid()
    {
        if ($this->status !== 'completed') {
            return false;
        }

        if ($this->expires_at && now()->isAfter($this->expires_at)) {
            return false;
        }

        return true;
    }
}
