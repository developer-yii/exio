<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PropertyFilterController extends Controller
{
    public function resultFilter(Request $request)
    {
        $city = $request->input('city');
        $search = $request->input('search');

        $projects = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location');

        if ($city) {
            $projects = $projects->where('city_id', $city);
        }
        if ($search) {
            $projects = $projects->where('project_name', 'like', '%' . $search . '%');
        }

        if (Auth::check()) {
            $projects = $projects->with('wishlistedByUsers');
        }

        $projects = $projects->orderBy('created_at', 'desc')->isActive()->paginate(10);

        $appraisal = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location')->where('appraisal_property', 'yes')->isActive()->paginate(10);
        $bestMatch = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location')->isActive()->orderBy('exio_suggest_percentage', 'desc')->paginate(10);

        $priceUnit = Project::$priceUnit;
        $propertyTypes = collect(Project::$propertyType)
            ->only(['residential', 'commercial'])
            ->toArray();
        $amenities = Amenity::where('status', 1)->isActive()->pluck('amenity_name', 'id');

        $property_sub_types = config('constants.property_sub_type');

        $bhks = [
            '1' => '1 BHK',
            '2' => '2 BHK',
            '3' => '3 BHK',
            '4' => '4 BHK',
            '5' => '5 BHK',
            '>5' => '> 5 BHK',
        ];

        $minMaxPrice = GeneralSetting::whereIn('key', ['min_price', 'max_price'])->get()->pluck('value', 'key')->toArray();

        $shortlistedCount = Project::with('wishlistedByUsers')->whereHas('wishlistedByUsers', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();

        return view('frontend.property.result-filter', compact('projects', 'priceUnit', 'appraisal', 'bestMatch', 'propertyTypes', 'amenities', 'property_sub_types', 'bhks', 'minMaxPrice', 'shortlistedCount'));
    }

    public function getProjectData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $city = $request->input('city');
        $search = $request->input('search');
        $property_type = $request->input('property_type');
        $property_sub_types = $request->input('property_sub_types');
        $bhk = $request->input('bhk');
        $amenities = $request->input('amenities');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');

        $projects = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location');

        if ($city) {
            $projects = $projects->where('city_id', $city);
        }

        if (Auth::check()) {
            $projects = $projects->with('wishlistedByUsers');
        }

        if ($property_type) {
            $projects = $projects->where(function ($query) use ($property_type) {
                $query->where('property_type', $property_type)
                    ->orWhere('property_type', 'both');
            });

            if ($property_sub_types) {
                $projects = $projects->whereIn('property_sub_types', $property_sub_types);

                $allowed = ['flat', 'house', 'bungalow', 'villa'];
                $exists = !empty(array_intersect($property_sub_types, $allowed));

                if ($property_type == 'residential' && $exists) {
                    // $projects = $projects->whereIn('bhks
                }
            }
        }


        if ($amenities) {
            foreach ($amenities as $amenity) {
                $projects = $projects->whereRaw('FIND_IN_SET(?, amenities)', [$amenity]);
            }
        }

        if ($search) {
            $projects = $projects->where(function ($query) use ($search) {
                $query->where('project_name', 'like', '%' . $search . '%')
                    ->orWhereHas('locality', function ($q) use ($search) {
                        $q->where('locality_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('builder', function ($q) use ($search) {
                        $q->where('builder_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($minPrice && $maxPrice) {
            $projects = $projects->whereRaw("
                (CASE
                    WHEN price_from_unit = 'crores' THEN price_from * 100
                    ELSE price_from
                END) >= ?
                AND
                (CASE
                    WHEN price_to_unit = 'crores' THEN price_to * 100
                    ELSE price_to
                END) <= ?
            ", [$minPrice, $maxPrice]);
        }

        $projects = $projects->isActive()->paginate($perPage);
        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $projects
        ]);
    }

    public function getAppraisalData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $appraisal = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location')->where('appraisal_property', 'yes')->isActive();

        if (Auth::check()) {
            $appraisal = $appraisal->with('wishlistedByUsers');
        }

        $appraisal = $appraisal->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $appraisal
        ]);
    }

    public function getBestMatchData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $bestMatch = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location')->isActive()->orderBy('exio_suggest_percentage', 'desc');

        if (Auth::check()) {
            $bestMatch = $bestMatch->with('wishlistedByUsers');
        }

        $bestMatch = $bestMatch->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $bestMatch
        ]);
    }

    public function getSingleProjectData(Request $request)
    {
        $project = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location', 'projectDetails');

        if (Auth::check()) {
            $project = $project->with('wishlistedByUsers');
        }

        $project = $project->find($request->input('id'));

        $project->video = asset('storage/project/videos') . '/' . $project->video;

        $project->property_type = Project::$propertyType[$project->property_type];

        $project->project_images = $project->projectImages->map(function ($image) {
            $image->image = asset('storage/project_images') . '/' . $image->image;
            return $image;
        });

        $project->is_wishlisted = $project->wishlistedByUsers->contains(auth()->id());
        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $project
        ]);
    }
}
