<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoDownload extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    /**
     * Get the user who downloaded the video
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that was downloaded
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Scope for recent downloads
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('downloaded_at', '>=', now()->subDays($days));
    }
}
