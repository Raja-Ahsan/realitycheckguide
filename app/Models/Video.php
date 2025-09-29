<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_intro' => 'boolean',
        'downloads_enabled' => 'boolean',
        'tags' => 'array',
        'price' => 'decimal:2',
        'videos_sold' => 'integer',
        'is_featured' => 'boolean',
        'tags_array' => 'array',
    ];

    /**
     * Get the creator of the video
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the category of the video
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get all purchases for this video
     */
    public function purchases()
    {
        return $this->hasMany(VideoPurchase::class);
    }

    /**
     * Get all downloads for this video
     */
    public function downloads()
    {
        return $this->hasMany(VideoDownload::class);
    }

    /**
     * Get all orders for this video
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if a user can access this video (free intro or purchased)
     */
    public function canUserAccess($userId = null)
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        if (!$userId) {
            return false;
        }

        // Free intro video is accessible to everyone
        if ($this->is_intro) {
            return true;
        }

        // Check if user has purchased this video
        return $this->purchases()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Check if a user can download this video
     */
    public function canUserDownload($userId = null)
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        if (!$userId) {
            return false;
        }

        // Downloads must be enabled
        if (!$this->downloads_enabled) {
            return false;
        }

        // Free intro video can be downloaded
        if ($this->is_intro) {
            return true;
        }

        // Check if user has purchased this video
        return $this->purchases()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get the video URL for streaming
     */
    public function getVideoUrlAttribute()
    {
        if (Storage::disk('public')->exists($this->video_path)) {
            return Storage::disk('public')->url($this->video_path);
        }
        return null;
    }

    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }
        return asset('public/assets/website/img/default-video-thumbnail.jpg');
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment purchase count
     */
    public function incrementPurchases()
    {
        $this->increment('purchases_count');
    }

    /**
     * Scope for free intro videos
     */
    public function scopeIntroVideos($query)
    {
        return $query->where('is_intro', true);
    }

    /**
     * Scope for paid videos
     */
    public function scopePaidVideos($query)
    {
        return $query->where('is_intro', false)->where('price', '>', 0);
    }

    /**
     * Scope for active videos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $seconds = (int) $this->duration;
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->is_intro) {
            return 'Free';
        }
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get all questions for this video
     */
    public function questions()
    {
        return $this->hasMany(VideoQuestion::class)->active()->ordered();
    }

    /**
     * Get all question responses for this video
     */
    public function questionResponses()
    {
        return $this->hasMany(VideoQuestionResponse::class);
    }

    /**
     * Get responses by a specific user for this video
     */
    public function userQuestionResponses($userId)
    {
        return $this->questionResponses()->where('user_id', $userId);
    }

    /**
     * Check if video has Q&A questions
     */
    public function hasQuestions()
    {
        return $this->questions()->count() > 0;
    }

    /**
     * Get total number of questions
     */
    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Calculate user's learning progress for this video
     */
    public function calculateUserProgress($userId)
    {
        $totalQuestions = $this->getTotalQuestionsAttribute();
        
        if ($totalQuestions === 0) {
            return [
                'total_questions' => 0,
                'answered_questions' => 0,
                'correct_answers' => 0,
                'progress_percentage' => 0,
                'learning_score' => 0,
            ];
        }

        $userResponses = $this->userQuestionResponses($userId)->get();
        $answeredQuestions = $userResponses->count();
        $correctAnswers = $userResponses->where('is_correct', true)->count();
        
        $progressPercentage = $answeredQuestions > 0 ? ($answeredQuestions / $totalQuestions) * 100 : 0;
        $learningScore = $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) * 100 : 0;

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'correct_answers' => $correctAnswers,
            'progress_percentage' => round($progressPercentage, 2),
            'learning_score' => round($learningScore, 2),
        ];
    }

    /**
     * Check if user has completed all questions for this video
     */
    public function hasUserCompletedQuestions($userId)
    {
        $totalQuestions = $this->getTotalQuestionsAttribute();
        $answeredQuestions = $this->userQuestionResponses($userId)->count();
        
        return $totalQuestions > 0 && $answeredQuestions >= $totalQuestions;
    }
}
