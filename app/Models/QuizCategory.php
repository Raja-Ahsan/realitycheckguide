<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all questions for this category
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'category_id');
    }

    /**
     * Get all results for this category
     */
    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class, 'category_id');
    }

    /**
     * Get active questions for this category
     */
    public function activeQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'category_id')->where('is_active', true);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get total questions count
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Get active questions count
     */
    public function getActiveQuestionsCountAttribute(): int
    {
        return $this->activeQuestions()->count();
    }
}
