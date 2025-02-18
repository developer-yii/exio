<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $guarded = [];

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    public static $propertyType = [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
    ];

    public static $priceUnit = [
        'lacs' => 'M',
        'crores' => 'Cr',
    ];

    public static $ageOfConstruction = [
        'under_construction' => 'Under Construction',
        'completed' => 'Completed',
    ];

    public static function getPropertySubTypes($propertyType)
    {
        return config('constants.property_sub_type')[$propertyType];
    }

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'projects';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function builder()
    {
        return $this->belongsTo(Builder::class, 'builder_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function projectDetails()
    {
        return $this->hasMany(ProjectdetailAddMore::class, 'project_id');
    }

    public function masterPlans()
    {
        return $this->hasMany(MasterPlanAddMore::class, 'project_id');
    }
}
