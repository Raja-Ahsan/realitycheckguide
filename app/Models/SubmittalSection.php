<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmittalSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'extracted_data' => 'array',
        'included' => 'boolean',
    ];

    public function submittal()
    {
        return $this->belongsTo(Submittal::class);
    }
}


