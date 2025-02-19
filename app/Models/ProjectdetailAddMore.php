<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectdetailAddMore extends Model
{
    use SoftDeletes;

    protected $table = 'projectdetail_add_mores';

    protected $fillable = ['project_id', 'name', 'value'];
}
