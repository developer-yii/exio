<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActualProgress extends Model
{
    use SoftDeletes;

    public const IN_PROGRESS = 0;
    public const COMPLETED = 1;


    public static $status = [
        self::IN_PROGRESS => 'In Progress',
        self::COMPLETED => 'Completed',
    ];

    protected $fillable = ['project_id', 'timeline', 'work_completed', 'description', 'status', 'date'];

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'actual_progress';

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

    public function images()
    {
        return $this->hasMany(ActualProgressImage::class);
    }
}
