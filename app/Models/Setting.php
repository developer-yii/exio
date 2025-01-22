<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'settings';

    protected $fillable = [
        'setting_key',
        'setting_label',
        'setting_value',
        'description',
        'status',
        'is_default',
        'created_by',
        'updated_by',
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
