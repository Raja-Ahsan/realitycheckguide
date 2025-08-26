<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bid extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function electrician()
    {
        return $this->belongsTo(User::class, 'electrician_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 