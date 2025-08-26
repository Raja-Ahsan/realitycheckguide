<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatorPricingRule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'videos_sold_threshold' => 'integer',
        'max_price_cap' => 'decimal:2',
        'min_price_floor' => 'decimal:2',
        'custom_pricing_enabled' => 'boolean',
        'pricing_tiers' => 'array',
    ];

    /**
     * Get the creator these rules apply to
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Check if creator can set custom pricing
     */
    public function canSetCustomPricing()
    {
        return $this->custom_pricing_enabled;
    }

    /**
     * Validate if a price is within allowed range
     */
    public function isPriceValid($price)
    {
        return $price >= $this->min_price_floor && $price <= $this->max_price_cap;
    }

    /**
     * Get the recommended price range
     */
    public function getPriceRange()
    {
        return [
            'min' => $this->min_price_floor,
            'max' => $this->max_price_cap,
        ];
    }
}
