<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use HasFactory;

    public function getProjectImageUrl()
    {
        $image = $this->image;
        if ($image) {
            $filePath = "public/project_images/{$image}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/project_images/' . $image);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
