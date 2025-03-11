<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyComparison extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'property_id_1', 'property_id_2'];
}
