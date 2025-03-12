<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyComparison extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'property_id_1', 'property_id_2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function propertyOne()
    {
        return $this->belongsTo(Project::class, 'property_id_1');
    }

    public function propertyTwo()
    {
        return $this->belongsTo(Project::class, 'property_id_2');
    }
}
