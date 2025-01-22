<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'cities';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'city_id');
    }
}
