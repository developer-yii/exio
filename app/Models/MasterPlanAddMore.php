<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPlanAddMore extends Model
{
    use SoftDeletes;

    protected $table = 'master_plan_add_mores';

    protected $fillable = ['project_id', 'name', '2d_image', '3d_image'];
}
