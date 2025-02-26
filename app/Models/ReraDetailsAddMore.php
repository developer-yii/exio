<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReraDetailsAddMore extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'title', 'document'];

    protected $table = 'rera_details_add_mores';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
