<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadBrochure extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'phone_number',
        'email'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
