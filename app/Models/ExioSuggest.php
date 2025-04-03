<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExioSuggest extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'weightage'];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_exio_suggest_points')
                    ->withPivot('point')
                    ->withTimestamps();
    }
}
