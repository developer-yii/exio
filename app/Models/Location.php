<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];



    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'locations';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'location_id');
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }
}
