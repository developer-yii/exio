<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Locality extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $table = 'localities';

    protected $fillable = ['locality_name', 'locality_image', 'status', 'created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getLocalityImageUrl()
    {
        $localityImg = $this->locality_image;
        if ($localityImg) {
            $filePath = "public/locality/image/{$localityImg}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/locality/image/' . $localityImg);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
