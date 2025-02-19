<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FloorPlanAddMore extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'carpet_area', 'type', '2d_image', '3d_image'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
