<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_question_id',
        'option_text',
        'option_order',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Get the question that owns this option
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(VideoQuestion::class, 'video_question_id');
    }

    /**
     * Get all responses that selected this option
     */
    public function responses(): HasMany
    {
        return $this->hasMany(VideoQuestionResponse::class, 'video_question_option_id');
    }

    /**
     * Scope for correct options
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect options
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Scope for ordered options
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('option_order');
    }
}