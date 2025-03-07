<?php

namespace App\Http\Controllers\Frondend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\DownloadBrochure;
use App\Models\Project;
use App\Models\PropertyWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    public function details($slug){
        $query = Project::with([
            'projectImages',
            'builder',
            'projectDetails',
            'masterPlans',
            'floorPlans',
            'localities',
            'localities.locality',
            'reraDetails'
        ]);

        // Include wishlistedByUsers only if user is authenticated
        if (Auth::check()) {
            $query->with('wishlistedByUsers');
        }

        $project = $query->where('slug', $slug)->first();

        // pr($project->toArray());

        if(!$project){
            abort('404');
        }

        $amenityIds = explode(',', $project->amenities);
        $amenitiesList = Amenity::whereIn('id', $amenityIds)->get();

        $similarProperties = Project::with(['projectImages', 'location', 'wishlistedByUsers', 'location.city'])->where('city_id', $project->city_id)
            ->where('location_id', $project->location_id)
            ->where('property_type', $project->property_type)
            // ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                $priceFrom = convertToLacs($project->price_from, $project->price_from_unit);
                $priceTo = convertToLacs($project->price_to, $project->price_to_unit);
                $tolerance = 0.1; // 10% flexibility

                // Calculate min and max price range with tolerance
                $minFromPrice = $priceFrom - ($priceFrom * $tolerance);
                $maxFromPrice = $priceFrom + ($priceFrom * $tolerance);
                $minToPrice = $priceTo - ($priceTo * $tolerance);
                $maxToPrice = $priceTo + ($priceTo * $tolerance);

                $query->where(function ($q) use ($minFromPrice, $maxFromPrice, $minToPrice, $maxToPrice) {
                    $q->whereRaw("
                        (IF(price_from_unit = 'crores', price_from * 100, price_from) BETWEEN ? AND ?)
                        OR
                        (IF(price_to_unit = 'crores', price_to * 100, price_to) BETWEEN ? AND ?)
                    ", [$minFromPrice, $maxFromPrice, $minToPrice, $maxToPrice]);
                });
            })
            ->take(3)
            ->get();

        return view('frontend.property.details', compact('project', 'amenitiesList', 'similarProperties'));
    }

    public function downloadBrochureForm(Request $request){
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $brochure = DownloadBrochure::create($request->only(['project_id', 'name', 'phone_number', 'email']));
        if($brochure){
            $project = Project::find($request->project_id);
            $brochureFile = $project ? $project->getDocumentUrl() : null;
            if ($brochureFile) {
                // Generate the correct public URL
                $publicUrl = asset(str_replace('public/', 'storage/', $brochureFile));

                return response()->json([
                    'status' => true,
                    'file' => $publicUrl
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Brochure file not found.']);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function addRemoveWishlist(Request $request){

        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $loginUser = Auth::user();

        $favourite_property = PropertyWishlist::where('project_id', $request->property_id)
                                    ->where('user_id', $loginUser->id)
                                    ->first();

        if($favourite_property){
            $favourite_property->delete();
            $result = ['status' => 'disliked', 'message' => 'Property removed successfully from whishlist.'];
        }else{
            $favourite_property = PropertyWishlist::create([
                'project_id' => $request->property_id,
                'user_id' => $loginUser->id,
            ]);
            $result = ['status' => 'liked', 'message' => 'Property added successfully from whishlist.'];
        }
        return response($result);

    }

    public function likedProperty(Request $request){

        $loginUser = Auth::user();
        $projectids = PropertyWishlist::where('user_id', $loginUser->id)->pluck('project_id');
        $favourite_properties = Project::with([
            'projectImages' => function ($query) {
                $query->where('is_cover', 0);
            },
            'builder',
            'location',
            'projectDetails',
            'city'
        ])->whereIn('id', $projectids)->paginate(9);

        return view('frontend.property.liked-properties', compact('favourite_properties'));
    }

    public function compareProperty(Request $request)
    {

        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $properties = Project::with([
            'projectImages' => function ($query) {
                $query->where('is_cover', 0);
            },
            'builder',
            'location',
            'projectDetails',
            'city'
        ])->whereIn('id', $request->ids)->take(2)->get();

        foreach($properties as $property){
            $property->possession_date = getFormatedDate($property->possession_by, 'M, Y');
            $property->cover_image = $property->getCoverImageUrl();
            $property->price = "₹" . $property->price_from . formatPriceUnit($property->price_from_unit)  ." - ₹ " . $property->price_to . formatPriceUnit($property->price_to_unit) ;
            $property->truncatedPropertyType = truncateText($property->custom_property_type, 15);
            $property->location_city = $property->location->location_name .",". $property->city->city_name;
            $property->truncatedLocation = truncateText($property->location_city, 15);

        }

        if(!$properties){
            return response()->json(['status' => false, 'message' => 'Property not found.', 'data' => []]);
        }

        return response(['status' => true, 'data' => $properties]);

    }

    public function comparePropertyPage(Request $request)
    {
        $encodedCompareIds = request()->get('property');
        $encodedArray = explode(',', $encodedCompareIds);

        $compareIds = array_map(function ($encodedId) {
            return base64_decode($encodedId);
        }, $encodedArray);

        $properties = Project::with([
            'projectImages',
            'builder',
            'projectDetails',
            'masterPlans',
            'floorPlans',
            'localities',
            'localities.locality',
            'reraDetails'
        ])->whereIn('id', $compareIds)->get();

        foreach($properties as $property){
            $amenityIds = explode(',', $property->amenities);
            $property->amenitiesList = Amenity::whereIn('id', $amenityIds)->get();
        }

        return view('frontend.property.comparepage', compact('properties'));
    }

    // public function comparePropertyPage(Request $request)
    // {
    //     $encodedCompareIds = request()->get('property');
    //     $encodedArray = explode(',', $encodedCompareIds);

    //     $compareIds = array_map(function ($encodedId) {
    //         return base64_decode($encodedId);
    //     }, $encodedArray);

    //     $propertyId1 = $compareIds[0] ?? null;
    //     $propertyId2 = $compareIds[1] ?? null;


    //     $properties = Project::with([
    //         'projectImages',
    //         'builder',
    //         'projectDetails',
    //         'masterPlans',
    //         'floorPlans',
    //         'localities',
    //         'localities.locality',
    //         'reraDetails'
    //     ])->whereIn('id', $compareIds)->get();

    //     foreach($properties as $property){
    //         $amenityIds = explode(',', $property->amenities);
    //         $property->amenitiesList = Amenity::whereIn('id', $amenityIds)->get();
    //     }

    //     return view('frontend.property.comparepage', compact('properties'));
    // }
}
