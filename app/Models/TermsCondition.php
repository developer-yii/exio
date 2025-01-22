<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsCondition extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'terms_conditions';

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'updated_by',
    ];
}
