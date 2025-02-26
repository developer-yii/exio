<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActualProgressImage extends Model
{
    use SoftDeletes;

    protected $fillable = ['actual_progress_id', 'image'];

    public function actualProgress()
    {
        return $this->belongsTo(ActualProgress::class);
    }
}
