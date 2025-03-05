<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MasterPlanAddMore extends Model
{
    use SoftDeletes;

    protected $table = 'master_plan_add_mores';

    protected $fillable = ['project_id', 'name', '2d_image', '3d_image'];

    public function get2DImageUrl()
    {
        $twoDImage = $this->{'2d_image'};
        if ($twoDImage) {
            $filePath = "public/master_plan/2d_image/{$twoDImage}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/master_plan/2d_image/' . $twoDImage);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }

    public function get3DImageUrl()
    {
        $threeDImage = $this->{'3d_image'};
        if ($threeDImage) {
            $filePath = "public/master_plan/3d_image/{$threeDImage}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/master_plan/3d_image/' . $threeDImage);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
