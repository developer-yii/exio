<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $guarded = [];

    public static $status = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    public static $propertyType = [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
        'both' => 'Both',
    ];

    public static $priceUnit = [
        'lacs' => 'L',
        'crores' => 'Cr',
    ];

    public static $ageOfConstruction = [
        'under_construction' => 'Under Construction',
        'completed' => 'Completed',
    ];

    public static $appraisalProperty = [
        'yes' => 'Yes',
        'no' => 'No',
    ];

    public static function getPropertySubTypes($propertyType)
    {
        if ($propertyType == 'both') {
            $propertySubTypes = config('constants.property_sub_type');
            return array_merge($propertySubTypes['residential'], $propertySubTypes['commercial']);
        }
        return config('constants.property_sub_type')[$propertyType];
    }

    protected $dates = ['created_at', 'updated_at'];

    protected $table = 'projects';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    public function builder()
    {
        return $this->belongsTo(Builder::class, 'builder_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function projectDetails()
    {
        return $this->hasMany(ProjectdetailAddMore::class, 'project_id');
    }

    public function masterPlans()
    {
        return $this->hasMany(MasterPlanAddMore::class, 'project_id');
    }

    public function floorPlans()
    {
        return $this->hasMany(FloorPlanAddMore::class, 'project_id');
    }

    public function localities()
    {
        return $this->hasMany(LocalityAddMore::class, 'project_id');
    }

    public function locality()
    {
        return $this->hasManyThrough(Locality::class, LocalityAddMore::class, 'project_id', 'id', 'id');
    }

    public function reraDetails()
    {
        return $this->hasMany(ReraDetailsAddMore::class, 'project_id');
    }

    public function projectImages()
    {
        return $this->hasMany(ProjectImage::class, 'project_id');
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'property_wishlists', 'project_id', 'user_id');
    }

    public function wishlist()
    {
        return $this->hasMany(PropertyWishlist::class, 'project_id');
    }

    public function getVideoUrl()
    {
        $video = $this->video;
        if ($video) {
            $filePath = "public/project/videos/{$video}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/project/videos/' . $video);  // Correct URL structure
            }
        }
        return '';  // Return an empty string if the file doesn't exist
    }

    public function getCoverImageUrl()
    {
        $coverImage = $this->cover_image;
        if ($coverImage) {
            $filePath = "public/project_images/{$coverImage}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/project_images/' . $coverImage);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }

    public function getDocumentUrl()
    {
        $document = $this->property_document;
        if ($document) {
            $filePath = "public/property_documents/{$document}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/property_documents/' . $document);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }

    public function getInsightReportPdfUrl()
    {
        $insights_report_file = $this->insights_report_file;
        if ($insights_report_file) {
            $filePath = "public/project/insights-reports/{$insights_report_file}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/project/insights-reports/' . $insights_report_file);  // Correct URL structure
            }
        }
        return '';
    }

    public function projectBadge()
    {
        return $this->belongsTo(ProjectBadge::class, 'project_badge', 'id');
    }

    public function downloadBrochures()
    {
        return $this->hasMany(DownloadBrochure::class);
    }

    public function actualProgress()
    {
        return $this->hasMany(ActualProgress::class, 'project_id');
    }

    public function reraProgress()
    {
        return $this->hasMany(ReraProgress::class, 'project_id');
    }

    public function comparisonsAsPropertyOne()
    {
        return $this->hasMany(PropertyComparison::class, 'property_id_1');
    }

    public function comparisonsAsPropertyTwo()
    {
        return $this->hasMany(PropertyComparison::class, 'property_id_2');
    }

    public function getProjectBadgeName()
    {
        return $this->projectBadge->name;
    }
  
    public function insightsReports()
    {
        return $this->hasMany(InsightsReportDownload::class, 'property_id');
    }

    public function exioSuggests()
    {
        return $this->belongsToMany(ExioSuggest::class, 'project_exio_suggest_points')
                    ->withPivot('point')
                    ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            // Delete related records
            InsightsReportDownload::where('property_id', $project->id)->delete();
            PropertyComparison::where('property_id_1', $project->id)->orWhere('property_id_2', $project->id)->delete();
        });
    }
}
