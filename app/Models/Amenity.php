<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use SoftDeletes;

    protected $fillable = ['amenity_name', 'amenity_icon', 'amenity_type', 'status', 'created_by', 'updated_by'];

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    public static $amenityType = [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
        'both' => 'Both',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
