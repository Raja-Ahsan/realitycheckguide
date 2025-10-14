<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_email',
        'category_id',
        'total_questions',
        'correct_answers',
        'score_percentage',
        'completed_at',
    ];

    protected $casts = [
        'score_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the category that this result belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(QuizCategory::class, 'category_id');
    }

    /**
     * Calculate score percentage
     */
    public static function calculateScorePercentage(int $correctAnswers, int $totalQuestions): float
    {
        if ($totalQuestions === 0) {
            return 0;
        }
        
        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    /**
     * Get score grade based on percentage
     */
    public function getScoreGradeAttribute(): string
    {
        $percentage = $this->score_percentage;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        
        return 'F';
    }

    /**
     * Scope for recent results
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('completed_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for results by category
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
