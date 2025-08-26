<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_editable',
    ];

    protected $casts = [
        'is_editable' => 'boolean',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set setting value by key
     */
    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general', string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'decimal' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Get commission rate setting
     */
    public static function getCommissionRate(): float
    {
        return static::getValue('commission_rate', 30.0);
    }

    /**
     * Get max video price setting
     */
    public static function getMaxVideoPrice(): float
    {
        return static::getValue('max_video_price', 99.99);
    }

    /**
     * Get min video price setting
     */
    public static function getMinVideoPrice(): float
    {
        return static::getValue('min_video_price', 0.99);
    }

    /**
     * Get videos sold threshold setting
     */
    public static function getVideosSoldThreshold(): int
    {
        return static::getValue('videos_sold_threshold', 15);
    }

    /**
     * Get Stripe settings
     */
    public static function getStripeSettings(): array
    {
        return [
            'publishable_key' => static::getValue('stripe_publishable_key'),
            'secret_key' => static::getValue('stripe_secret_key'),
            'webhook_secret' => static::getValue('stripe_webhook_secret'),
            'currency' => static::getValue('stripe_currency', 'usd'),
        ];
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults(): void
    {
        $defaults = [
            [
                'key' => 'commission_rate',
                'value' => '30.0',
                'type' => 'decimal',
                'group' => 'commission',
                'description' => 'Default commission rate for video sales (%)',
            ],
            [
                'key' => 'max_video_price',
                'value' => '99.99',
                'type' => 'decimal',
                'group' => 'video',
                'description' => 'Maximum price a creator can set for videos',
            ],
            [
                'key' => 'min_video_price',
                'value' => '0.99',
                'type' => 'decimal',
                'group' => 'video',
                'description' => 'Minimum price a creator can set for videos',
            ],
            [
                'key' => 'videos_sold_threshold',
                'value' => '15',
                'type' => 'integer',
                'group' => 'video',
                'description' => 'Number of videos creator must sell to unlock custom pricing',
            ],
            [
                'key' => 'stripe_currency',
                'value' => 'usd',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Default currency for Stripe payments',
            ],
        ];

        foreach ($defaults as $setting) {
            static::setValue(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group'],
                $setting['description']
            );
        }
    }
}
