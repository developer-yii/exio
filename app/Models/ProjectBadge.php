<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBadge extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    protected $fillable = ['slug', 'name', 'status', 'created_by', 'updated_by'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $table = 'project_badges';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'project_badge', 'id');
    }
}
