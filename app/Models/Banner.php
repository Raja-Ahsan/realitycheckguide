<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope for specific slug
     */
    public function scopeForSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
