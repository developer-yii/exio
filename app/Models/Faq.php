<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $table= 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'order_index',
        'status',
        'created_by',
        'updated_by',
    ];
}
