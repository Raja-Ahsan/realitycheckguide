<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submittal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sections()
    {
        return $this->hasMany(SubmittalSection::class);
    }

    public function coverTemplate()
    {
        return $this->belongsTo(CoverTemplate::class, 'cover_template_id');
    }

    public function timelineEvents()
    {
        return $this->hasMany(SubmittalTimelineEvent::class);
    }
}


