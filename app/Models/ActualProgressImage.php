<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ActualProgressImage extends Model
{
    use SoftDeletes;

    protected $fillable = ['actual_progress_id', 'image'];

    public function actualProgress()
    {
        return $this->belongsTo(ActualProgress::class);
    }

    public function getProgressImageUrl()
    {
        $image = $this->image;
        if ($image) {
            $filePath = "public/actual_progress_images/{$image}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/actual_progress_images/' . $image);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
