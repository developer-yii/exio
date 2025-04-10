<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    const ADMIN = 1;
    const USER = 2;
    const EMPLOYEE = 3;

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    public static $role = [
        1 => 'Admin',
        2 => 'User',
        3 => 'Employee',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'remember_token',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function wishlist()
    {
        return $this->hasMany(PropertyWishlist::class, 'user_id');
    }

    public function wishedProjects()
    {
        return $this->belongsToMany(Project::class, 'property_wishlists', 'user_id', 'project_id');
    }

    public function propertyComparisons()
    {
        return $this->hasMany(PropertyComparison::class);
    }

    public function insightsReports()
    {
        return $this->hasMany(InsightsReportDownload::class, 'user_id');
    }


    public function forums()
    {
        return $this->hasMany(Forum::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete all forums associated with this user
            foreach ($user->forums as $forum) {
                $forum->answers()->delete(); // Delete answers related to the forum
                $forum->delete(); // Delete the forum
            }
        });
    }
}
