<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
