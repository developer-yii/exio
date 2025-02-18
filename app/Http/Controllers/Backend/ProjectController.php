<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\City;
use App\Models\Location;
use App\Models\MasterPlanAddMore;
use App\Models\Project;
use App\Models\ProjectdetailAddMore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

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
            ->addColumn('status_text', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? "";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->editColumn('updated_by', function ($row) {
                return (isset($row->updatedBy->id)) ? $row->updatedBy->first_name . " " . $row->updatedBy->last_name : "";
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
            'city_id' => 'required',
            'area_id' => 'required',
            'builder_id' => 'required',
            'property_type' => 'required',
            'property_sub_types' => 'required',
            'project_about' => 'required',
            'possession_by' => 'required',
            'rera_number' => 'required',
            'price_from' => 'required',
            'price_from_unit' => 'required',
            'price_to' => 'required',
            'price_to_unit' => 'required',
            'carpet_area' => 'required',
            'total_floors' => 'required',
            'total_tower' => 'required',
            'age_of_construction' => 'required',
            'project_status' => 'required',
            'project_detail' => 'required|array',
            'project_detail.*.name' => 'required|string|max:100',
            'project_detail.*.value' => 'required|string|max:100',
            'master_plan' => 'required|array',
            'master_plan.*.name' => 'required|string|max:100',
            'master_plan.*.2d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'master_plan.*.3d_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'project_detail.*.name.required' => 'The project detail name field is required.',
            'project_detail.*.value.required' => 'The project detail value field is required.',
            'master_plan.*.name.required' => 'The master plan name field is required.',
            'master_plan.*.2d_image.required' => 'The master plan 2D image field is required.',
            'master_plan.*.2d_image.mimes' => 'The master plan 2D image must be a file of type: jpeg, png, jpg, gif, svg.',
            'master_plan.*.3d_image.required' => 'The master plan 3D image field is required.',
            'master_plan.*.3d_image.mimes' => 'The master plan 3D image must be a file of type: jpeg, png, jpg, gif, svg.',
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
        $model->project_about = $request->project_about;
        $model->city_id  = $request->city_id;
        $model->location_id = $request->area_id;
        $model->builder_id = $request->builder_id;
        $model->property_type = $request->property_type;
        $model->property_sub_types = $request->property_sub_types;
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
        $model->updated_by = auth()->id();
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
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/master_plan/2d_image', $filename);
                    $masterPlanAddMore['2d_image'] = $filename;
                }
                if ($request->hasFile('master_plan.' . $index . '.3d_image')) {
                    if ($masterPlanAddMore['3d_image'] && Storage::exists('public/master_plan/3d_image/' . $masterPlanAddMore['3d_image'])) {
                        Storage::delete('public/master_plan/3d_image/' . $masterPlanAddMore['3d_image']);
                    }

                    $file = $request->file('master_plan.' . $index . '.3d_image');
                    $filename = time() . '_' . $file->getClientOriginalName();
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
            $model->updated_by_view = (isset($model->updatedBy->id)) ? $model->updatedBy->first_name . " " . $model->updatedBy->last_name : "";
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

        $builder = Builder::pluck('builder_name', 'id');
        $city = City::pluck('city_name', 'id');
        $area = Location::pluck('location_name', 'id');

        $propertyTypes = Project::$propertyType;
        $priceUnit = Project::$priceUnit;
        $projectStatus = Project::$status;
        $ageOfConstruction = Project::$ageOfConstruction;

        $existingProjectDetails = $model->projectDetails;
        $existingMasterPlans = $model->masterPlans;

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
            'existingMasterPlans'
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

        return view('backend.project.addupdate', compact('model', 'builder', 'city', 'area', 'propertyTypes', 'priceUnit', 'projectStatus', 'ageOfConstruction'));
    }

    public function view($id)
    {
        $model = Project::findOrFail($id);

        $status = Project::$status;
        $model->project_logo_url = asset('storage/project/logo/' . $model->project_logo);
        $model->status_text = isset($status[$model->status]) ? $status[$model->status] : '';
        $model->created_at_view = $model->created_at ? date('d-m-Y g:i A', strtotime($model->created_at)) : '';
        $model->updated_at_view = $model->updated_at ? date('d-m-Y g:i A', strtotime($model->updated_at)) : '';
        $model->updated_by_view = isset($model->updatedBy->id) ? $model->updatedBy->first_name . ' ' . $model->updatedBy->last_name : '';

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
