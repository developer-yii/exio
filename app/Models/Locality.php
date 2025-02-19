<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
