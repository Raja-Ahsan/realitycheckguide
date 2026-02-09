<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberDirectory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function hasCreatedBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getCategoryTitlesAttribute()
    {
        $ids = json_decode($this->category_id ?? '[]');
        return \App\Models\Category::whereIn('id', $ids)->pluck('title')->toArray();
    }

}
