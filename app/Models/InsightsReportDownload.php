<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsightsReportDownload extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'property_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Project::class, 'property_id');
    }
}
