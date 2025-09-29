<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'question',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the video that owns this question
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Get all options for this question
     */
    public function options(): HasMany
    {
        return $this->hasMany(VideoQuestionOption::class)->orderBy('option_order');
    }

    /**
     * Get all responses for this question
     */
    public function responses(): HasMany
    {
        return $this->hasMany(VideoQuestionResponse::class);
    }

    /**
     * Get the correct option for this question
     */
    public function correctOption(): HasMany
    {
        return $this->hasMany(VideoQuestionOption::class)->where('is_correct', true);
    }

    /**
     * Check if question has exactly 4 options
     */
    public function hasValidOptions(): bool
    {
        return $this->options()->count() === 4;
    }

    /**
     * Check if question has exactly one correct answer
     */
    public function hasValidCorrectAnswer(): bool
    {
        return $this->options()->where('is_correct', true)->count() === 1;
    }

    /**
     * Check if question is valid (has 4 options and 1 correct answer)
     */
    public function isValid(): bool
    {
        return $this->hasValidOptions() && $this->hasValidCorrectAnswer();
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered questions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}