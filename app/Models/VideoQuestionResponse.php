<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoQuestionResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'video_question_id',
        'video_question_option_id',
        'is_correct',
        'answered_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'answered_at' => 'datetime',
    ];

    /**
     * Get the user who answered
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video this response belongs to
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Get the question this response belongs to
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(VideoQuestion::class, 'video_question_id');
    }

    /**
     * Get the option that was selected
     */
    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(VideoQuestionOption::class, 'video_question_option_id');
    }

    /**
     * Scope for correct responses
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect responses
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Scope for responses by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for responses by video
     */
    public function scopeByVideo($query, $videoId)
    {
        return $query->where('video_id', $videoId);
    }
}