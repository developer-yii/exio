<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExioSuggestPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'exio_suggest_id',
        'point'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function exioSuggest()
    {
        return $this->belongsTo(ExioSuggest::class);
    }
}
