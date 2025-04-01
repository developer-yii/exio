<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 0;
    const APPROVE = 1;

    public static $status = [
        1 => 'Approve',
        0 => 'Pending',
    ];

    protected $fillable = ['user_id', 'question', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(ForumAnswer::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($forum) {
            $forum->answers()->delete(); // Delete all related answers
        });
    }

}
