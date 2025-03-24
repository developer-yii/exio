<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ActualProgress;
use App\Models\Amenity;
use App\Models\Builder;
use App\Models\City;
use App\Models\DownloadBrochure;
use App\Models\InsightsReportDownload;
use App\Models\Locality;
use App\Models\Location;
use App\Models\Project;
use App\Models\PropertyComparison;
use App\Models\PropertyWishlist;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class PropertyController extends Controller
{
    public function details(Request $request, $slug){
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

        $project = $query->where('slug', $slug)->where('status', 1)->first();

        // pr($project->toArray());

        if(!$project){
            abort('404');
        }

        $amenityIds = explode(',', $project->amenities);
        $amenitiesList = Amenity::whereIn('id', $amenityIds)->get();

        $similarProperties = Project::with(['projectImages', 'location', 'wishlistedByUsers', 'location.city'])
            ->where('city_id', $project->city_id)
            ->where('location_id', $project->location_id)
            ->where('property_type', $project->property_type)
            ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                $priceFrom = convertCrToL($project->price_from, $project->price_from_unit);
                $priceTo = convertCrToL($project->price_to, $project->price_to_unit);
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
        $favourite_properties = projectQuery()->whereIn('id', $projectids)->paginate(9);

        return view('frontend.property.liked-properties', compact('favourite_properties'));
    }

    public function compareProperty(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $properties = projectQuery()->whereIn('id', $request->ids)->take(2)->get();

        foreach($properties as $property){
            $property->possession_date = getFormatedDate($property->possession_by, 'M, Y');
            $property->cover_image = $property->getCoverImageUrl();

            $property->price = formatPriceRange($property->price_from, $property->price_from_unit, $property->price_to, $property->price_to_unit);

            // $property->truncatedPropertyType = truncateText($property->custom_property_type, 15);
            $property->location_city = $property->location->location_name .",". $property->city->city_name;
            // $property->truncatedLocation = truncateText($property->location_city, 15);

        }

        if(!$properties){
            return response()->json(['status' => false, 'message' => 'Property not found.', 'data' => []]);
        }

        return response(['status' => true, 'data' => $properties]);

    }

    public function comparePropertyPage(Request $request)
    {
        $encodedCompareIds = $request->get('property');
        $compareIds = array_map('base64_decode', explode(',', $encodedCompareIds));
        $properties = getPropertiesWithDetails($compareIds);

        if ($request->has('download') && $request->download == 'pdf') {
            if (!auth()->check()) {
                return redirect()->back()->with('error', 'Please login first to download the report.');
            }

            if (count($compareIds) >= 2) {
                // Check if a record already exists for the user and same properties
                $existingComparison = PropertyComparison::where('user_id', Auth::id())
                    ->where(function ($query) use ($compareIds) {
                        $query->where([
                            ['property_id_1', $compareIds[0]],
                            ['property_id_2', $compareIds[1]]
                        ])->orWhere([
                            ['property_id_1', $compareIds[1]],
                            ['property_id_2', $compareIds[0]]
                        ]);
                    })
                    ->first();

                if ($existingComparison) {
                    $existingComparison->touch();
                } else {
                    PropertyComparison::create([
                        'user_id' => Auth::id(),
                        'property_id_1' => $compareIds[0],
                        'property_id_2' => $compareIds[1],
                    ]);
                }
            }
            return generatePdf('pdf.compare_report', compact('properties'), 'compare_report.pdf');
        }
        return view('frontend.property.comparepage', compact('properties'));
    }

    public function compareDownload(Request $request, $reportId){
        $report = propertyComparisonQuery()->findOrFail($reportId);
        $properties = getPropertiesWithDetails([$report->property_id_1, $report->property_id_2]);

        return generatePdf('pdf.compare_report', compact('properties'), 'compare_report.pdf');
    }

    public function compareReport(Request $request)
    {
        $compareReports = propertyComparisonQuery()->paginate(5);
        return view('frontend.property.compare-reports', compact('compareReports'));
    }

    public function propertyInsights(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $city = $request->query('city');
        $search = $request->query('search');

        $perPageProperty = 9;
        $page = $request->input('page', 1);
        $properties = projectQuery();

        if ($type && $id) {
            if ($type === 'builder') {
                $properties->where('builder_id', $id);
            } elseif ($type === 'locality') {
                $properties->where('location_id', $id);
            } elseif ($type === 'project') {
                $properties->where('id', $id);
            }
        }elseif ($search){
            $properties->where(function ($query) use ($search) {
                $query->where('project_name', 'LIKE', "%$search%")
                    ->orWhereHas('location', function ($q) use ($search) {
                        $q->where('location_name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('builder', function ($q) use ($search) {
                        $q->where('builder_name', 'LIKE', "%$search%");
                    });
            });
        }

        if (isset($city) && $city != 'All') {
            $properties->whereHas('city', function ($query) use ($city) {
                $query->where('city_name', $city);
            });
        }

        $properties->orderByRaw("
            COALESCE(
                (SELECT updated_at
                FROM actual_progress
                WHERE actual_progress.project_id = projects.id
                AND actual_progress.deleted_at IS NULL
                ORDER BY updated_at DESC
                LIMIT 1
                ),
                projects.updated_at
            ) DESC
        ");
        $totalProperties = (clone $properties)->count();

        $properties = $properties->paginate($perPageProperty);
        if ($request->ajax()) {
            return response()->json([
                'properties' => view('frontend.property.partial_insights_property_list', compact('properties'))->render(),
                'hasMore' => $properties->hasMorePages()
            ]);
        }

        $cities = City::where('status', 1)->get();
        $locations = Location::where('status', 1)->get();
        $projects = Project::where('status', 1)->get();
        $builders = Builder::where('status', 1)->get();

        return view('frontend.property.insights', compact('properties', 'totalProperties', 'perPageProperty', 'cities', 'locations', 'projects', 'builders'));
    }

    public function insightDetails($slug){
        $query = Project::with([
            'builder',
            'actualProgress' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Order actualProgress by created_at DESC
            },
            'actualProgress.images' // Load images within actualProgress
        ]);

        // Include wishlistedByUsers only if user is authenticated
        if (Auth::check()) {
            $query->with('wishlistedByUsers');
        }

        $project = $query->where('slug', $slug)->where('status', 1)->first();

        if(!$project){
            abort('404');
        }

        $progressStatus = ActualProgress::$status;

        $actualProgressData = [];
        $actualProgressData = $project->actualProgress->sortBy('created_at')->map(function ($progress) {
            return [
                'timeline' => (int) $progress->timeline,
                'work_completed' => (int) $progress->work_completed
            ];
        })->values()->toArray();

        $reraProgressData = $project->reraProgress->sortBy('created_at')->map(function ($progress) {
            return [
                'timeline' => (int) $progress->timeline,
                'work_completed' => (int) $progress->work_completed
            ];
        })->values()->toArray();
        return view('frontend.property.insight-details', compact('project', 'progressStatus', 'actualProgressData', 'reraProgressData'));
    }

    public function downloadInsightsReport(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please login first to download the report.'], 401);
        }

        $project = Project::findOrFail($request->id);
        $filePath = storage_path("app/public/project/insights-reports/{$project->insights_report_file}");

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $existingDownload = InsightsReportDownload::where('user_id', Auth::id())
            ->where('property_id', $request->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if (!$existingDownload) {
            InsightsReportDownload::create([
                'user_id' => Auth::id(),
                'property_id' => $request->id,
            ]);
        }

        return Response::download($filePath);
    }

    public function insightsReports(Request $request){
        $insightsReports = InsightsReportDownload::select('property_id')
            ->with('property', 'property.builder', 'property.location', 'property.city')
            ->where('user_id', Auth::id())
            ->groupBy('property_id')
            ->paginate(10);

        return view('frontend.property.insights-reports', compact('insightsReports'));
    }
}
