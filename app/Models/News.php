<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    public function getNewsImgUrl()
    {
        $builderLogo = $this->image;
        if ($builderLogo) {
            $filePath = "public/news/image/{$builderLogo}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/news/image/' . $builderLogo);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
