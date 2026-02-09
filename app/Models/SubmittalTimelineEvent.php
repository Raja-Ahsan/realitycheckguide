<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittalTimelineEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'happened_at' => 'datetime',
    ];

    public function submittal()
    {
        return $this->belongsTo(Submittal::class);
    }
}


