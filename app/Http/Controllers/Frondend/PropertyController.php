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

        $similarProperties = Project::with(['location', 'wishlistedByUsers'])->where('city_id', $project->city_id)
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
}
