<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Builder;
use App\Models\City;
use App\Models\Location;
use App\Models\MasterPlanAddMore;
use App\Models\Project;
use App\Models\ProjectdetailAddMore;
use App\Models\FloorPlanAddMore;
use App\Models\Locality;
use App\Models\LocalityAddMore;
use App\Models\ProjectImage;
use App\Models\ReraDetailsAddMore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Project::$status;
        return view('backend.project.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = Project::$status;

        $sqlQuery = Project::select('projects.*')
            ->leftJoin('cities', 'projects.city_id', '=', 'cities.id')
            ->leftJoin('builders', 'projects.builder_id', '=', 'builders.id')
            ->leftJoin('locations', 'projects.location_id', '=', 'locations.id')
            ->with('city:id,city_name', 'builder:id,builder_name', 'location:id,location_name');

        return DataTables::eloquent($sqlQuery)
            ->editColumn('project_logo', function ($row) {
                return ($row->project_logo) ? '<img src="' . asset('storage/project/logo/' . $row->project_logo) . '" alt="Project Logo" style="width: 50px; height: 50px;">' : "";
            })
            ->editColumn('project_about', function ($row) {
                return ($row->project_about) ? \Illuminate\Support\Str::limit($row->project_about, 20) : "";
            })
            ->editColumn('builder_name', function ($row) {
                return ($row->builder->builder_name) ? $row->builder->builder_name : "";
            })
            ->editColumn('city_name', function ($row) {
                return ($row->city->city_name) ? $row->city->city_name : "";
            })
            ->editColumn('location_name', function ($row) {
                return ($row->location->location_name) ? $row->location->location_name : "";
            })
            ->editColumn('rera_progress', function ($row) {
                return "<a href='" . route('admin.rera_progress', ['project_id' => $row->id]) . "'>RERA Progress</a>";
            })
            ->editColumn('actual_progress', function ($row) {
                return "<a href='" . route('admin.actual_progress', ['project_id' => $row->id]) . "'>Actual Progress</a>";
            })
            ->editColumn('property_type', function ($row) {
                return $row->property_type ? Project::$propertyType[$row->property_type] : '';
            })
            ->addColumn('status_text', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? "";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->editColumn('updated_by', function ($row) {
                return (isset($row->updatedBy->id)) ? $row->updatedBy->name : "";
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d-m-Y');
            })
            ->filter(function ($query) use ($request) {
                if ($filterDate = $request->get('filter_date')) {
                    if (strtotime($filterDate)) {
                        $formattedDate = date('Y-m-d', strtotime($filterDate));
                        $query->whereDate('projects.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('projects.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('projects.project_name', 'LIKE', "%$searchValue%")
                            ->orWhere('city_name', 'LIKE', "%$searchValue%");
                    });
                }
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function addupdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $isUpdate = (!empty($request->id) && $request->id) ? true : false;
        $cityId = (!empty($request->city_id) && $request->city_id) ? $request->city_id : 0;
        $areaId = (!empty($request->area_id) && $request->area_id) ? $request->area_id : 0;

        $rules = [
            'project_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('projects', 'project_name')->ignore($request->id)->whereNull('deleted_at')->where('builder_id', $request->builder_id)
            ],
            'slug' => [
                'nullable',
                'string',
            ],
            'video' => [
                Rule::requiredIf(!$isUpdate),
                'file',
                File::types(['mp4', 'mov', 'ogg', 'qt', 'avi', 'flv', 'wmv', '3gp', 'webm'])->max(10240),
            ],
            'city_id' => 'required',
            'area_id' => 'required',
            'builder_id' => 'required',
            'property_type' => 'required',
            'property_sub_types' => 'required',
            'custom_property_type' => 'required',
            'project_about' => 'required',
            'possession_by' => 'required',
            'rera_number' => 'required',
            'price_from' => 'required',
            'price_to' => 'required',
            'carpet_area' => 'required',
            'total_floors' => 'required',
            'total_tower' => 'required',
            'age_of_construction' => 'required',
            'project_status' => 'required',
            'exio_suggest_percentage' => 'required|numeric',
            'amenities_percentage' => 'required|numeric',
            'project_plan_percentage' => 'required|numeric',
            'locality_percentage' => 'required|numeric',
            'return_of_investment_percentage' => 'required|numeric',
            'project_detail' => 'required|array',
            'project_detail.*.name' => 'required|string|max:100',
            'project_detail.*.value' => 'required|string|max:100',
            'master_plan' => 'required|array',
            'master_plan.*.name' => 'required|string|max:100',
            'master_plan.*.2d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'master_plan.*.3d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'floor_plan' => 'required|array',
            'floor_plan.*.carpet_area' => 'required|string|max:100',
            'floor_plan.*.type' => 'required|string|max:100',
            'floor_plan.*.2d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'floor_plan.*.3d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'locality' => 'required|array',
            'locality.*.locality_id' => 'required',
            'locality.*.distance' => 'required',
            'locality.*.distance_unit' => 'required',
            'locality.*.time_to_reach' => 'required',
            'property_document' => 'nullable|mimes:pdf|max:10240',
            'property_document_title' => 'nullable|required_with:property_document|string|max:255',
            'address' => 'required|string|max:255',
            'rera_details' => 'required|array',
            'rera_details.*.title' => 'required|string|max:255',
            'rera_details.*.document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
            'project_images' => 'required|array',
            'project_images.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'project_detail.*.name.required' => 'The project detail name field is required.',
            'project_detail.*.value.required' => 'The project detail value field is required.',
            'master_plan.*.name.required' => 'The master plan name field is required.',
            'master_plan.*.2d_image.required' => 'The master plan 2D image field is required.',
            'master_plan.*.2d_image.mimes' => 'The master plan 2D image must be a file of type: jpeg, png, jpg, gif, svg.',
            'master_plan.*.3d_image.required' => 'The master plan 3D image field is required.',
            'master_plan.*.3d_image.mimes' => 'The master plan 3D image must be a file of type: jpeg, png, jpg, gif, svg.',
            'floor_plan.*.carpet_area.required' => 'The floor plan carpet area field is required.',
            'floor_plan.*.type.required' => 'The floor plan type field is required.',
            'floor_plan.*.2d_image.mimes' => 'The floor plan 2D image must be a file of type: jpeg, png, jpg, gif, svg.',
            'floor_plan.*.3d_image.mimes' => 'The floor plan 3D image must be a file of type: jpeg, png, jpg, gif, svg.',
            'locality.*.locality_id.required' => 'The locality field is required.',
            'locality.*.distance.required' => 'The distance field is required.',
            'locality.*.distance_unit.required' => 'The distance unit field is required.',
            'locality.*.time_to_reach.required' => 'The time to reach field is required.',
            'address.required' => 'The address field is required.',
            'rera_details.*.title.required' => 'The title field is required.',
            'rera_details.*.document.mimes' => 'The document must be a file of type: pdf, doc, docx, xls, xlsx, ppt, pptx.',
            'project_images.*.image.required' => 'The image field is required.',
            'project_images.*.image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Project::find($request->id) : new Project;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Builder not found']);
        }

        $model->project_name = ucwords(strtolower(trim($request->project_name)));
        $slug = !empty($request->slug) ? Str::slug($request->slug) : Str::slug($request->project_name);
        $originalSlug = $slug;
        $count = 1;

        while (Project::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $model->slug = $slug;
        $model->project_about = $request->project_about;
        $model->city_id  = $request->city_id;
        $model->location_id = $request->area_id;
        $model->builder_id = $request->builder_id;
        $model->property_type = $request->property_type;
        $model->property_sub_types = $request->property_sub_types;
        $model->custom_property_type = $request->custom_property_type;
        $model->possession_by = date('Y-m-d', strtotime($request->possession_by));
        $model->rera_number = $request->rera_number;
        $model->price_from = $request->price_from;
        $model->price_from_unit = $request->price_from_unit;
        $model->price_to = $request->price_to;
        $model->price_to_unit = $request->price_to_unit;
        $model->carpet_area = $request->carpet_area;
        $model->total_floors = $request->total_floors;
        $model->total_tower = $request->total_tower;
        $model->age_of_construction = $request->age_of_construction;
        $model->status = $request->boolean('project_status', false);
        $model->address = $request->address;
        $model->latitude = $request->latitude;
        $model->longitude = $request->longitude;
        $model->exio_suggest_percentage = $request->exio_suggest_percentage;
        $model->amenities_percentage = $request->amenities_percentage;
        $model->project_plan_percentage = $request->project_plan_percentage;
        $model->locality_percentage = $request->locality_percentage;
        $model->return_of_investment_percentage = $request->return_of_investment_percentage;
        $model->updated_by = auth()->id();

        //save amenities
        $amenities = $request->amenities;
        $amenities = (isset($amenities) && !empty($amenities)) ? implode(',', $amenities) : '';
        $model->amenities = $amenities;

        if ($request->hasFile('property_document')) {
            if (isset($model->property_document) && !empty($model->property_document) && Storage::exists('public/property_documents/' . $model->property_document)) {
                Storage::delete('public/property_documents/' . $model->property_document);
            }

            $file = $request->file('property_document');
            $fileName = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/property_documents', $fileName);
            $model->property_document = $fileName;
            $model->property_document_title = $request->property_document_title;
        }

        if ($request->hasFile('video')) {
            if (isset($model->video) && !empty($model->video) && Storage::exists('public/project/videos/' . $model->video)) {
                Storage::delete('public/project/videos/' . $model->video);
            }

            $file = $request->file('video');
            $fileName = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/project/videos', $fileName);
            $model->video = $fileName;
        }

        if (!$isUpdate) {
            $model->created_by = auth()->id();
        }

        if ($model->save()) {
            $existingIds = ProjectdetailAddMore::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedIds = [];

            foreach ($request->project_detail as $projectDetail) {
                $projectDetailAddMore = $projectDetail['id'] ? ProjectdetailAddMore::find($projectDetail['id']) : new ProjectdetailAddMore;
                $projectDetailAddMore->project_id = $model->id;
                $projectDetailAddMore->name = $projectDetail['name'];
                $projectDetailAddMore->value = $projectDetail['value'];
                $projectDetailAddMore->save();

                if ($projectDetail['id']) {
                    $updatedIds[] = $projectDetail['id'];
                }
            }

            $idsToDelete = array_diff($existingIds, $updatedIds);
            if (!empty($idsToDelete)) {
                ProjectdetailAddMore::whereIn('id', $idsToDelete)->delete();
            }

            //save master plan add more
            $existingMasterPlanIds = MasterPlanAddMore::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedMasterPlanIds = [];

            foreach ($request->master_plan as $index => $masterPlan) {
                $masterPlanAddMore = $masterPlan['id'] ? MasterPlanAddMore::find($masterPlan['id']) : new MasterPlanAddMore;
                $masterPlanAddMore->project_id = $model->id;
                $masterPlanAddMore->name = $masterPlan['name'];
                if ($request->hasFile('master_plan.' . $index . '.2d_image')) {
                    if ($masterPlanAddMore['2d_image'] && Storage::exists('public/master_plan/2d_image/' . $masterPlanAddMore['2d_image'])) {
                        Storage::delete('public/master_plan/2d_image/' . $masterPlanAddMore['2d_image']);
                    }

                    $file = $request->file('master_plan.' . $index . '.2d_image');
                    $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/master_plan/2d_image', $filename);
                    $masterPlanAddMore['2d_image'] = $filename;
                }
                if ($request->hasFile('master_plan.' . $index . '.3d_image')) {
                    if ($masterPlanAddMore['3d_image'] && Storage::exists('public/master_plan/3d_image/' . $masterPlanAddMore['3d_image'])) {
                        Storage::delete('public/master_plan/3d_image/' . $masterPlanAddMore['3d_image']);
                    }

                    $file = $request->file('master_plan.' . $index . '.3d_image');
                    $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/master_plan/3d_image', $filename);
                    $masterPlanAddMore['3d_image'] = $filename;
                }
                $masterPlanAddMore->save();

                if ($masterPlan['id']) {
                    $updatedMasterPlanIds[] = $masterPlan['id'];
                }
            }

            $idsToDelete = array_diff($existingMasterPlanIds, $updatedMasterPlanIds);
            if (!empty($idsToDelete)) {
                MasterPlanAddMore::whereIn('id', $idsToDelete)->delete();
            }

            // Process floor plans
            $existingFloorPlanIds = FloorPlanAddMore::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedFloorPlanIds = [];

            foreach ($request->floor_plan as $index => $floorPlan) {
                $floorPlanAddMore = $floorPlan['id'] ? FloorPlanAddMore::find($floorPlan['id']) : new FloorPlanAddMore;
                $floorPlanAddMore->project_id = $model->id;
                $floorPlanAddMore->carpet_area = $floorPlan['carpet_area'];
                $floorPlanAddMore->type = $floorPlan['type'];

                if ($request->hasFile('floor_plan.' . $index . '.2d_image')) {
                    if ($floorPlanAddMore['2d_image'] && Storage::exists('public/floor_plan/2d_image/' . $floorPlanAddMore['2d_image'])) {
                        Storage::delete('public/floor_plan/2d_image/' . $floorPlanAddMore['2d_image']);
                    }

                    $file = $request->file('floor_plan.' . $index . '.2d_image');
                    $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/floor_plan/2d_image', $filename);
                    $floorPlanAddMore['2d_image'] = $filename;
                }

                if ($request->hasFile('floor_plan.' . $index . '.3d_image')) {
                    if ($floorPlanAddMore['3d_image'] && Storage::exists('public/floor_plan/3d_image/' . $floorPlanAddMore['3d_image'])) {
                        Storage::delete('public/floor_plan/3d_image/' . $floorPlanAddMore['3d_image']);
                    }

                    $file = $request->file('floor_plan.' . $index . '.3d_image');
                    $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/floor_plan/3d_image', $filename);
                    $floorPlanAddMore['3d_image'] = $filename;
                }

                $floorPlanAddMore->save();

                if ($floorPlan['id']) {
                    $updatedFloorPlanIds[] = $floorPlan['id'];
                }
            }

            $idsToDelete = array_diff($existingFloorPlanIds, $updatedFloorPlanIds);
            if (!empty($idsToDelete)) {
                FloorPlanAddMore::whereIn('id', $idsToDelete)->delete();
            }

            // Process localities
            $existingLocalityIds = LocalityAddMore::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedLocalityIds = [];

            if ($request->has('locality')) {
                foreach ($request->locality as $locality) {
                    $localityAddMore = !empty($locality['id'])
                        ? LocalityAddMore::find($locality['id'])
                        : new LocalityAddMore;

                    $localityAddMore->project_id = $model->id;
                    $localityAddMore->locality_id = $locality['locality_id'];
                    $localityAddMore->distance = $locality['distance'];
                    $localityAddMore->distance_unit = $locality['distance_unit'];
                    $localityAddMore->time_to_reach = $locality['time_to_reach'];
                    $localityAddMore->save();

                    if (!empty($locality['id'])) {
                        $updatedLocalityIds[] = $locality['id'];
                    }
                }
            }

            $idsToDelete = array_diff($existingLocalityIds, $updatedLocalityIds);
            if (!empty($idsToDelete)) {
                LocalityAddMore::whereIn('id', $idsToDelete)->delete();
            }

            // Save RERA details
            $existingReraDetailsIds = ReraDetailsAddMore::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedReraDetailsIds = [];

            foreach ($request->rera_details as $index => $detail) {
                $reraDetail = $detail['id'] ? ReraDetailsAddMore::find($detail['id']) : new ReraDetailsAddMore;
                $reraDetail->project_id = $model->id;
                $reraDetail->title = $detail['title'];

                if ($request->hasFile('rera_details.' . $index . '.document')) {
                    $file = $request->file('rera_details.' . $index . '.document');
                    $fileName = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/rera_documents', $fileName);
                    $reraDetail->document = $fileName;
                }

                $reraDetail->save();

                $updatedReraDetailsIds[] = $reraDetail->id;
            }

            $idsToDelete = array_diff($existingReraDetailsIds, $updatedReraDetailsIds);
            if (!empty($idsToDelete)) {
                ReraDetailsAddMore::whereIn('id', $idsToDelete)->delete();
            }

            // Save project images
            $existingProjectImagesIds = ProjectImage::where('project_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedProjectImagesIds = [];

            foreach ($request->project_images as $index => $images) {
                if (!isset($images['id']) && !$request->hasFile('project_images.' . $index . '.image')) {
                    continue;
                }

                $projectImages = $images['id'] ? ProjectImage::find($images['id']) : new ProjectImage;
                $projectImages->project_id = $model->id;
                $projectImages->is_cover = isset($images['is_cover']) && $images['is_cover'] == 'on' ? 1 : 0;

                if ($request->hasFile('project_images.' . $index . '.image')) {
                    if ($projectImages->image && Storage::exists('public/project_images/' . $projectImages->image)) {
                        Storage::delete('public/project_images/' . $projectImages->image);
                    }

                    $file = $request->file('project_images.' . $index . '.image');
                    $fileName = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/project_images', $fileName);
                    $projectImages->image = $fileName;
                }

                if (isset($images['is_cover']) && $images['is_cover'] == 'on') {
                    if ($images['id']) {
                        $model->cover_image = $projectImages->image;
                    } else {
                        $model->cover_image = $fileName ?? null;
                    }
                    $model->save();
                }


                $projectImages->save();

                $updatedProjectImagesIds[] = $projectImages->id;
            }

            $idsToDelete = array_diff($existingProjectImagesIds, $updatedProjectImagesIds);
            if (!empty($idsToDelete)) {
                ProjectImage::whereIn('id', $idsToDelete)->delete();
            }

            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $status = Project::$status;
        $model = Project::find($request->id);
        if (isset($model->id)) {
            $model->project_logo_url = asset('storage/project/logo/' . $model->project_logo);
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d-m-Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d-m-Y g:i A', strtotime($model->updated_at)) : "";
            $model->updated_by_view = (isset($model->updatedBy->id)) ? $model->updatedBy->name : "";
            if ($model->updatedBy) {
                unset($model->updatedBy);
            }

            $result = ['status' => true, 'message' => '', 'data' => $model];
        } else {
            $result = ['status' => false, 'message' => 'Invalid request', 'data' => []];
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $model = Project::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }

    public function edit($id)
    {
        $model = Project::with(['projectDetails', 'masterPlans'])->find($id);
        if (!$model) {
            return redirect()->route('admin.project')->with('error', 'Project not found.');
        }

        $amenities = explode(',', $model->amenities);
        $model->amenities = $amenities;

        $builder = Builder::pluck('builder_name', 'id');
        $city = City::pluck('city_name', 'id');
        $area = Location::pluck('location_name', 'id');

        $propertyTypes = Project::$propertyType;
        $priceUnit = Project::$priceUnit;
        $projectStatus = Project::$status;
        $ageOfConstruction = Project::$ageOfConstruction;

        $existingProjectDetails = $model->projectDetails;
        $existingMasterPlans = $model->masterPlans;
        $existingFloorPlans = $model->floorPlans;
        $amenities = Amenity::where('status', 1)->pluck('amenity_name', 'id');
        $locality = Locality::where('status', 1)->pluck('locality_name', 'id');
        $existingLocalities = $model->localities;
        $existingReraDetails = $model->reraDetails;
        $existingProjectImages = $model->projectImages;

        return view('backend.project.addupdate', compact(
            'model',
            'builder',
            'city',
            'area',
            'propertyTypes',
            'priceUnit',
            'projectStatus',
            'ageOfConstruction',
            'existingProjectDetails',
            'existingMasterPlans',
            'existingFloorPlans',
            'amenities',
            'locality',
            'existingLocalities',
            'existingReraDetails',
            'existingProjectImages'
        ));
    }

    public function add(Request $request)
    {
        $model = Project::find($request->id);

        $builder = Builder::pluck('builder_name', 'id');
        $city = City::pluck('city_name', 'id');
        $area = Location::pluck('location_name', 'id');

        $propertyTypes = Project::$propertyType;
        $priceUnit = Project::$priceUnit;
        $projectStatus = Project::$status;
        $ageOfConstruction = Project::$ageOfConstruction;
        $amenities = Amenity::where('status', 1)->pluck('amenity_name', 'id');
        $locality = Locality::where('status', 1)->pluck('locality_name', 'id');

        return view('backend.project.addupdate', compact('model', 'builder', 'city', 'area', 'propertyTypes', 'priceUnit', 'projectStatus', 'ageOfConstruction', 'amenities', 'locality'));
    }

    public function view($id)
    {
        $model = Project::findOrFail($id);

        $status = Project::$status;
        $model->project_logo_url = asset('storage/project/logo/' . $model->project_logo);
        $model->status_text = isset($status[$model->status]) ? $status[$model->status] : '';
        $model->created_at_view = $model->created_at ? date('d-m-Y g:i A', strtotime($model->created_at)) : '';
        $model->updated_at_view = $model->updated_at ? date('d-m-Y g:i A', strtotime($model->updated_at)) : '';
        $model->updated_by_view = isset($model->updatedBy->id) ? $model->updatedBy->name : '';

        if ($model->updatedBy) {
            unset($model->updatedBy);
        }

        return view('backend.project.view', compact('model'));
    }

    public function getPropertySubTypes(Request $request)
    {
        $propertySubTypes = Project::getPropertySubTypes($request->property_type);
        return response()->json(['status' => true, 'message' => '', 'data' => $propertySubTypes]);
    }
}
