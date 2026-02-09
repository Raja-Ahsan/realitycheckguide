<?php

namespace App\Services;

use App\Models\Video;
use App\Models\User;
use App\Models\VideoPurchase;
use App\Models\CreatorPricingRule;
use Illuminate\Support\Facades\Cache;

class VideoAccessService
{
    /**
     * Check if a user can access a video
     */
    public function canUserAccessVideo(User $user, Video $video): bool
    {
        // Free intro videos are accessible to everyone
        if ($video->is_intro) {
            return true;
        }

        // Check if user has purchased this video
        return $this->hasUserPurchasedVideo($user, $video);
    }

    /**
     * Check if a user can download a video
     */
    public function canUserDownloadVideo(User $user, Video $video): bool
    {
        // Downloads must be enabled
        if (!$video->downloads_enabled) {
            return false;
        }

        // Free intro videos can be downloaded
        if ($video->is_intro) {
            return true;
        }

        // Check if user has purchased this video
        return $this->hasUserPurchasedVideo($user, $video);
    }

    /**
     * Check if user has purchased a video
     */
    public function hasUserPurchasedVideo(User $user, Video $video): bool
    {
        $cacheKey = "user_{$user->id}_video_{$video->id}_purchase";
        
        return Cache::remember($cacheKey, 300, function () use ($user, $video) {
            return VideoPurchase::where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->where('status', 'completed')
                ->exists();
        });
    }

    /**
     * Get user's available videos (free intro + purchased)
     */
    public function getUserAvailableVideos(User $user): array
    {
        $introVideos = Video::introVideos()
            ->active()
            ->with(['creator', 'category'])
            ->get();

        $purchasedVideos = Video::whereHas('purchases', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed');
        })
        ->active()
        ->with(['creator', 'category'])
        ->get();

        return [
            'intro_videos' => $introVideos,
            'purchased_videos' => $purchasedVideos,
            'total_available' => $introVideos->count() + $purchasedVideos->count()
        ];
    }

    /**
     * Validate video pricing for a creator
     */
    public function validateVideoPricing(User $creator, float $price): array
    {
        if (!$creator->isCreator()) {
            return [
                'valid' => false,
                'message' => 'Only creators can set video prices.'
            ];
        }

        $pricingRules = $creator->pricingRules;
        
        if (!$pricingRules) {
            // Default rules for new creators
            $defaultMin = 0.99;
            $defaultMax = 19.99;
            
            if ($price < $defaultMin || $price > $defaultMax) {
                return [
                    'valid' => false,
                    'message' => "Price must be between \${$defaultMin} and \${$defaultMax} for new creators."
                ];
            }
            
            return [
                'valid' => true,
                'message' => 'Price is valid for new creator.'
            ];
        }

        if (!$pricingRules->custom_pricing_enabled) {
            // Check if creator meets threshold
            $totalSold = $creator->getTotalVideosSoldAttribute();
            $threshold = $pricingRules->videos_sold_threshold;
            
            if ($totalSold < $threshold) {
                $remaining = $threshold - $totalSold;
                return [
                    'valid' => false,
                    'message' => "You need to sell {$remaining} more videos to unlock custom pricing."
                ];
            }
        }

        // Validate against pricing rules
        if (!$pricingRules->isPriceValid($price)) {
            $range = $pricingRules->getPriceRange();
            return [
                'valid' => false,
                'message' => "Price must be between \${$range['min']} and \${$range['max']}."
            ];
        }

        return [
            'valid' => true,
            'message' => 'Price is valid.'
        ];
    }

    /**
     * Check if creator can unlock custom pricing
     */
    public function canCreatorUnlockCustomPricing(User $creator): array
    {
        if (!$creator->isCreator()) {
            return [
                'can_unlock' => false,
                'message' => 'Only creators can unlock custom pricing.'
            ];
        }

        $totalSold = $creator->getTotalVideosSoldAttribute();
        $pricingRules = $creator->pricingRules;

        if (!$pricingRules) {
            return [
                'can_unlock' => false,
                'videos_sold' => $totalSold,
                'threshold' => 15,
                'message' => 'You need to sell at least 15 videos to unlock custom pricing.'
            ];
        }

        if ($pricingRules->custom_pricing_enabled) {
            return [
                'can_unlock' => true,
                'videos_sold' => $totalSold,
                'threshold' => $pricingRules->videos_sold_threshold,
                'message' => 'Custom pricing is already unlocked!'
            ];
        }

        if ($totalSold >= $pricingRules->videos_sold_threshold) {
            // Unlock custom pricing
            $pricingRules->update(['custom_pricing_enabled' => true]);
            
            return [
                'can_unlock' => true,
                'videos_sold' => $totalSold,
                'threshold' => $pricingRules->videos_sold_threshold,
                'message' => 'Congratulations! Custom pricing has been unlocked.'
            ];
        }

        $remaining = $pricingRules->videos_sold_threshold - $totalSold;
        return [
            'can_unlock' => false,
            'videos_sold' => $totalSold,
            'threshold' => $pricingRules->videos_sold_threshold,
            'remaining' => $remaining,
            'message' => "You need to sell {$remaining} more videos to unlock custom pricing."
        ];
    }

    /**
     * Get recommended pricing for a creator
     */
    public function getRecommendedPricing(User $creator): array
    {
        if (!$creator->isCreator()) {
            return [];
        }

        $pricingRules = $creator->pricingRules;
        
        if (!$pricingRules) {
            return [
                'min' => 0.99,
                'max' => 19.99,
                'recommended' => 9.99,
                'message' => 'Default pricing range for new creators'
            ];
        }

        $range = $pricingRules->getPriceRange();
        $recommended = ($range['min'] + $range['max']) / 2;

        return [
            'min' => $range['min'],
            'max' => $range['max'],
            'recommended' => round($recommended, 2),
            'message' => 'Based on your pricing rules'
        ];
    }

    /**
     * Clear user's video access cache
     */
    public function clearUserVideoCache(User $user, Video $video): void
    {
        $cacheKey = "user_{$user->id}_video_{$video->id}_purchase";
        Cache::forget($cacheKey);
    }

    /**
     * Get video access statistics
     */
    public function getVideoAccessStats(Video $video): array
    {
        $totalViews = $video->views_count;
        $totalPurchases = $video->purchases_count;
        $conversionRate = $totalViews > 0 ? round(($totalPurchases / $totalViews) * 100, 2) : 0;
        $revenue = $totalPurchases * $video->price;

        return [
            'total_views' => $totalViews,
            'total_purchases' => $totalPurchases,
            'conversion_rate' => $conversionRate,
            'revenue' => $revenue,
            'is_intro' => $video->is_intro,
            'price' => $video->price
        ];
    }
}
