<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRepository extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    // Relationship with the Project model
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id'); // Assuming 'project_id' is the foreign key
    }

    public function hasCreatedBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function hasCategory()
    {
        return $this->hasOne(ProjectCategory::class, 'slug', 'parent_id');
    }


}
