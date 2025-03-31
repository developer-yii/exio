<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Builder;
use App\Models\GeneralSetting;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PropertyFilterController extends Controller
{
    public function resultFilter(Request $request)
    {
        $city = $request->input('city');
        $search = $request->input('search');

        $projects = projectQuery();

        $projects = $projects->with('projectBadge', 'floorPlans');

        if ($city) {
            $projects = $projects->where('city_id', $city);
        }

        if ($search) {
            $projects = $projects->where(function ($query) use ($search) {
                $query->where('project_name', 'like', '%' . $search . '%')
                    ->orWhereHas('location', function ($q) use ($search) {
                        $q->where('location_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('builder', function ($q) use ($search) {
                        $q->where('builder_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // if ($search) {
        //     $projects = $projects->where('project_name', 'like', '%' . $search . '%');
        // }

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
        $amenities = Amenity::where('status', 1)->isActive()->get();
        $allAmenities = Amenity::where('status', 1)->isActive()->pluck('amenity_name', 'id');

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

        // for search box
        $sLocations = Location::where('status', 1)->get();
        $sProjects = Project::where('status', 1)->get();
        $sBuilders = Builder::where('status', 1)->get();

        return view('frontend.property.result-filter', compact('sLocations', 'sProjects', 'sBuilders', 'projects', 'priceUnit', 'appraisal', 'bestMatch', 'propertyTypes', 'amenities', 'allAmenities', 'property_sub_types', 'bhks', 'minMaxPrice', 'shortlistedCount'));
    }

    public function getProjectData(Request $request)
    {
        $filterApply = filter_var($request->filterApply, FILTER_VALIDATE_BOOLEAN);        
        $perPage = $request->input('perPage', 10);
        $city = $request->input('city');
        $search = $request->input('search');
        $property_type = $request->input('property_type');
        $property_sub_types = $request->input('property_sub_types');
        $bhk = $request->input('bhk');
        $amenities = $request->input('amenities');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');

        $minPrice = round($minPrice / 100000, 2);
        $maxPrice = round($maxPrice / 100000, 2);

        $projects = projectQuery();
        $projects = $projects->with('projectBadge', 'floorPlans');

        if (Auth::check()) {
            $projects = $projects->with('wishlistedByUsers');
        }

        if ($city) {
            $projects = $projects->where('city_id', $city);
        }  

        if($filterApply){
            if ($property_type) {
                $projects = $projects->where(function ($query) use ($property_type) {
                    $query->where('property_type', $property_type)
                        ->orWhere('property_type', 'both');
                });
    
                if ($property_sub_types) {
                    // $projects = $projects->whereIn('property_sub_types', $property_sub_types);
                    $projects = $projects->where(function ($query) use ($property_sub_types) {
                        foreach ($property_sub_types as $sub_type) {
                            $query->orWhereRaw("FIND_IN_SET(?, property_sub_types)", [$sub_type]);
                        }
                    });
    
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
        }
        else if ($search && !$filterApply) {
            $projects = $projects->where(function ($query) use ($search) {
                $query->where('project_name', 'like', '%' . $search . '%')
                    ->orWhereHas('location', function ($q) use ($search) {
                        $q->where('location_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('builder', function ($q) use ($search) {
                        $q->where('builder_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // if ($minPrice && $maxPrice) {
        //     $projects = $projects->whereRaw("
        //         (CASE
        //             WHEN price_from_unit = 'crores' THEN price_from * 100
        //             ELSE price_from
        //         END) >= ?
        //         OR
        //         (CASE
        //             WHEN price_to_unit = 'crores' THEN price_to * 100
        //             ELSE price_to
        //         END) <= ?
        //     ", [$minPrice, $maxPrice]);
        // }

        if ($minPrice && $maxPrice) {
            $projects = $projects->where(function ($query) use ($minPrice, $maxPrice) {
                $query->whereRaw("
                    (CASE
                        WHEN price_from_unit = 'crores' THEN price_from * 100
                        ELSE price_from
                    END) BETWEEN ? AND ?
                ", [$minPrice, $maxPrice])
                ->orWhereRaw("
                    (CASE
                        WHEN price_to_unit = 'crores' THEN price_to * 100
                        ELSE price_to
                    END) BETWEEN ? AND ?
                ", [$minPrice, $maxPrice]);
            });
        }        

        $projects = $projects->isActive()->paginate($perPage);

        foreach ($projects as $project) {
            $project->formatted_description = formattedProjectAbout($project->project_about);
            $project->is_wishlisted = $project->wishlistedByUsers->contains(auth()->id());
        }

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $projects
        ]);
    }

    public function getAppraisalData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $appraisal = projectQuery();

        $appraisal = $appraisal->with('projectBadge', 'floorPlans')->where('appraisal_property', 'yes');

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
        $bestMatch = projectQuery();

        $bestMatch = $bestMatch->with('projectBadge', 'floorPlans')->isActive()->orderBy('exio_suggest_percentage', 'desc');

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
        $project = projectQuery();

        $project = $project->with('projectBadge', 'floorPlans');

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
