<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Builder extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'builders';

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
        return $this->hasMany(Project::class, 'builder_id', 'id');
    }

    public function getBuilderLogoUrl()
    {
        $builderLogo = $this->builder_logo;
        if ($builderLogo) {
            $filePath = "public/builder/logo/{$builderLogo}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/builder/logo/' . $builderLogo);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
