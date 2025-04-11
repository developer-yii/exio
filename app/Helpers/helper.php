<?php

use App\Models\Amenity;
use App\Models\GeneralSetting;
use App\Models\Project;
use App\Models\PropertyComparison;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('pr')) {
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        exit();
    }
}
if (! function_exists('cacheclear')) {
    function cacheclear()
    {
        return time();
    }
}
if (! function_exists('getDateFormateView')) {
    function getDateFormateView($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }
}
if (! function_exists('addPageJsLink')) {
    function addPageJsLink($link)
    {
        return asset('backend/js/pages') . "/" . $link . '?' . time();
    }
}
if (! function_exists('frontendPageJsLink')) {
    function frontendPageJsLink($link)
    {
        return asset('frontend/assest/js/pages') . "/" . $link . '?' . time();
    }
}
if (!function_exists('isActiveRouteMain')) {
    function isActiveRouteMain($routeNames = "")
    {
        if ($routeNames) {
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'menuitem-active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'menuitem-active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeNames = "")
    {
        if ($routeNames) {
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return (auth()->check() && auth()->user()->role_type == 1) ? true : false;
    }
}
if (! function_exists('getSettingFromDb')) {
    function getSettingFromDb($key, $default = null)
    {
        $setting = \App\Models\Setting::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }
}
if (!function_exists('getSetting')) {
    function getSetting($option_key, $default = '')
    {
        $settings = config('settings');
        return $settings[$option_key] ?? $default;
    }
}

if (!function_exists('formatChar')) {
    function formatChar($value, $length = 35)
    {
        // Return empty string if value is null or empty
        if (empty($value)) {
            return '';
        }

        // Convert to string if not already
        $value = (string) $value;

        // Get string length
        $strLength = mb_strlen($value);

        // Return original string if shorter than max length
        if ($strLength <= $length) {
            return $value;
        }

        // Trim string and add ellipsis
        return mb_substr($value, 0, $length) . '...';
    }
}
if (!function_exists('ckEditoruploadImage')) {
    function ckEditoruploadImage(Request $request, string $defaultFolder = 'uploads', int $maxSize = 5120)
    {
        // Validate the request
        $folder = $request->query('folder', $defaultFolder);

        $request->validate([
            'upload' => "required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:$maxSize",
        ]);

        $image = $request->file('upload');

        // Generate unique filename
        $fileName = time() . '_' . Str::random(20) . '.' . $image->getClientOriginalExtension();
        $relativePath = "public/{$folder}/editor_images/" . $fileName; // Relative path

        // Store the file
        $path = Storage::put($relativePath, file_get_contents($image));

        if (!$path) {
            return [
                'uploaded' => false,
                'error'    => ['message' => 'Failed to upload image.'],
            ];
        }

        // Return success response
        return [
            "uploaded" => 1,
            "fileName" => $fileName,
            "url"      => Storage::url($relativePath),
        ];
    }
}

if (!function_exists('getDeviceType')) {
    function getDeviceType() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $mobileDevices = ['iPhone', 'Android', 'Windows Phone', 'BlackBerry'];

        foreach ($mobileDevices as $device) {
            if (stripos($userAgent, $device) !== false) {
                return 'mobile';
            }
        }

        return 'desktop';
    }
}

if (!function_exists('formatPriceUnit')) {
    function formatPriceUnit($price, $unit, $space = true) {

        $price = ((float)$price);
        $price_unit =  Project::$priceUnit[$unit];

        if($space){
            return $price . " " . $price_unit;
        }else{
            return $price . $price_unit;
        }
    }
}

if (!function_exists('getPropertyType')) {
    function getPropertyType($property_type) {
        if ($property_type === 'both') {
            // Exclude 'Both' and return only 'Residential, Commercial'
            return implode(', ', array_diff(Project::$propertyType, ['Both']));
        }
        return Project::$propertyType[$property_type] ?? 'Unknown';
    }
}


if (!function_exists('getAgeOfConstruction')) {
    function getAgeOfConstruction($age_of_construction) {
        return Project::$ageOfConstruction[$age_of_construction] ?? 'Unknown';
    }
}

// if (! function_exists('getFormatedDate')) {
//     function getFormatedDate($date, $format)
//     {
//         return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($format);
//     }
// }

if (! function_exists('getFormatedDate')) {
    function getFormatedDate($date, $format)
    {
        if (empty($date)) {
            return '-'; // Default fallback
        }
        return \Carbon\Carbon::parse($date)->format($format);
    }
}


if (!function_exists('getProgressBarColorClass')) {
    function getProgressBarColorClass($percentage)
    {
        if ($percentage <= 25) return 'yellowLight';        // 0 - 25% (Red)
        if ($percentage <= 50) return 'yellow';     // 26 - 50% (Orange)
        if ($percentage <= 75) return 'brown';     // 51 - 75% (Yellow)
        return 'green';                             // 76 - 100% (Green)
    }
}

if (!function_exists('convertCrToL')) {
    function convertCrToL($price, $unit)
    {
        if ($unit == 'crores') {
            return $price * 100; // 1 Crore = 100 Lacs
        }
        return $price; // Already in Lacs
    }
}

if (!function_exists('getAmenitiesList')) {

    function getAmenitiesList($amenities)
    {
        if (!$amenities) {
            return '';
        }

        $amenitiesArray = Amenity::whereIn('id', explode(',', $amenities))
            ->pluck('amenity_name')
            ->toArray();

        return implode(', ', $amenitiesArray);
    }
}

if (!function_exists('truncateText')) {
    function truncateText($text, $maxLength) {
        return (strlen($text) > $maxLength) ? substr($text, 0, $maxLength) . "..." : $text;
    }
}

if (!function_exists('renderProgressBar')) {
    function renderProgressBar($percentage)
    {
        return '<div class="progress-bar ' . getProgressBarColorClass($percentage) . '"
                    role="progressbar"
                    aria-valuenow="' . $percentage . '"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    style="width:' . $percentage . '%">
                </div>';
    }
}

if (!function_exists('propertyComparisonQuery')) {
    function propertyComparisonQuery()
    {
        $loginUserId = Auth::id();
        return PropertyComparison::with(['propertyOne', 'propertyTwo'])
            ->where('user_id', $loginUserId)
            ->whereHas('propertyOne', fn($q) => $q->where('status', 1))
            ->whereHas('propertyTwo', fn($q) => $q->where('status', 1));
    }
}

if (!function_exists('generatePdf')) {
    function generatePdf($view, $data = [], $fileName = 'document.pdf')
    {
        $pdf = PDF::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isPhpEnabled', true);

        return $pdf->download($fileName);
        // return $pdf->stream('compare_report.pdf');
    }
}

if (!function_exists('projectQuery')) {
    function projectQuery()
    {
        return Project::with([
            'projectImages' => function ($query) {
                $query->where('is_cover', 0);
            },
            'builder',
            'location',
            'projectDetails',
            'city'
        ])
        ->where('status', 1);
    }
}

if (!function_exists('getPropertiesWithDetails')) {
    function getPropertiesWithDetails(array $propertyIds)
    {
        return Project::with([
            'projectImages',
            'builder',
            'projectDetails',
            'masterPlans',
            'floorPlans',
            'localities.locality',
            'reraDetails'
        ])
        ->whereIn('id', $propertyIds)
        ->where('status', 1)
        ->get()
        ->map(function ($property) {
            $property->amenitiesList = Amenity::whereIn('id', explode(',', $property->amenities))->get();
            return $property;
        });
    }
}

if (!function_exists('hasDifferentPrices')) {
    function hasDifferentPrices($property) {
        return $property->price_from != $property->price_to || $property->price_from_unit != $property->price_to_unit;
    }
}

if (!function_exists('getCheckAndMatchVideoPath')) {
    function getCheckAndMatchVideoPath($video)
    {
        // $video = Setting::where('setting_key', 'check_match_video')->value('setting_value');
        if (!$video) {
            return "";
        }

        $videoPath = "public/check-match-property-video/" . $video;

        if (Storage::disk('local')->exists($videoPath)) {
            return asset('storage/check-match-property-video/' . $video);
        }

        return "";
    }
}

if (!function_exists('formattedProjectAbout')) {
    function formattedProjectAbout($about)
    {
        return htmlentities($about, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('setMinMaxPrice')) {
    function setMinMaxPrice()
    {
        $projects = Project::select('price_from', 'price_from_unit', 'price_to', 'price_to_unit')->get();

        $minPrice = null;
        $maxPrice = null;

        foreach ($projects as $project) {
            $fromPrice = convertToLacs($project->price_from, $project->price_from_unit);
            $toPrice = convertToLacs($project->price_to, $project->price_to_unit);

            if ($fromPrice !== null) {
                $minPrice = is_null($minPrice) ? $fromPrice : min($minPrice, $fromPrice);
            }
            if ($toPrice !== null) {
                $maxPrice = is_null($maxPrice) ? $toPrice : max($maxPrice, $toPrice);
            }
        }

        if (!is_null($minPrice)) {
            GeneralSetting::where('key', 'min_price')->update(['value' => $minPrice]);
        }
        if (!is_null($maxPrice)) {
            GeneralSetting::where('key', 'max_price')->update(['value' => $maxPrice]);
        }
    }
}

if (!function_exists('convertToLacs')) {
    function convertToLacs($price, $unit)
    {
        if ($unit == 'crores') {
            $price = $price * 100; // 1 Crore = 100 Lacs
        }
        return $price * 100000; // Already in Lacs
    }
}

function formatBudget($amount) {
    if ($amount >= 10000000) {
        return round($amount / 10000000, 2) . "Cr"; // Convert to Crores
    } elseif ($amount >= 100000) {
        return round($amount / 100000, 2) . "L"; // Convert to Lakhs
    }
    return number_format($amount); // Default formatting for smaller values
}

if (!function_exists('formatPriceRange')) {
    function formatPriceRange($priceFrom, $priceFromUnit, $priceTo, $priceToUnit) {
        $formattedPrice = '₹ ' . formatPriceUnit($priceFrom, $priceFromUnit);

        if ($priceFrom != $priceTo || $priceFromUnit != $priceToUnit) {
            $formattedPrice .= ' - ₹ ' . formatPriceUnit($priceTo, $priceToUnit);
        }

        return $formattedPrice;
    }
}

if (!function_exists('formatPriceRangeSingleSign')) {
    function formatPriceRangeSingleSign($priceFrom, $priceFromUnit, $priceTo, $priceToUnit) {
        $formattedPrice = '₹' . formatPriceUnit($priceFrom, $priceFromUnit, false);

        if ($priceFrom != $priceTo || $priceFromUnit != $priceToUnit) {
            $formattedPrice .= '-' . formatPriceUnit($priceTo, $priceToUnit, false);
        }

        return $formattedPrice;
    }
}

// if (!function_exists('exioSuggestSectionData')) {
//     function exioSuggestSectionData() {
//         return Setting::whereIn('setting_key', ['section-a', 'section-b', 'section-c', 'section-d'])
//                         ->pluck('setting_value', 'setting_key');
//     }
// }

if (!function_exists('exioSuggestSectionData')) {
    function exioSuggestSectionData() {
        return Setting::whereIn('setting_key', ['section-a', 'section-b', 'section-c', 'section-d'])
                        ->select('setting_key', 'setting_value', 'description')
                        ->get()
                        ->keyBy('setting_key');
    }
}



