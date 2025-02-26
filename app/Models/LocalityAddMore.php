<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalityAddMore extends Model
{
    use SoftDeletes;

    protected $table = 'locality_add_mores';

    protected $fillable = ['project_id', 'locality_id', 'distance', 'distance_unit', 'time_to_reach'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }
}
