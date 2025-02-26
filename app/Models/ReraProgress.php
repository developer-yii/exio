<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReraProgress extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'timeline', 'work_completed'];

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'rera_progress';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
