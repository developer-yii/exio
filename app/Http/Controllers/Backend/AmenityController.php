<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AmenityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Amenity::$status;
        $amenityType = Amenity::$amenityType;
        return view('backend.amenity.index', compact('status', 'amenityType'));
    }

    public function get(Request $request)
    {
        $statusLabels = Amenity::$status;
        $amenityTypeLabels = Amenity::$amenityType;

        $sqlQuery = Amenity::select('amenities.*');

        return DataTables::eloquent($sqlQuery)
            ->editColumn('amenity_icon', function ($row) {
                return ($row->amenity_icon) ? '<img src="' . asset('storage/amenity/icon/' . $row->amenity_icon) . '" alt="Amenity Icon" style="width: 50px; height: 50px;">' : "";
            })
            ->addColumn('amenity_type', function ($row) use ($amenityTypeLabels) {
                return $amenityTypeLabels[$row->amenity_type] ?? "";
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
                        $query->whereDate('amenities.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('amenities.status', $filterStatus);
                }

                if (($filterAmenityType = $request->get('filter_amenity_type')) !== null) {
                    $query->where('amenities.amenity_type', $filterAmenityType);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('amenities.amenity_name', 'LIKE', "%$searchValue%")
                            ->orWhere('amenities.amenity_type', 'LIKE', "%$searchValue%");
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

        $rules = [
            'amenity_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('amenities', 'amenity_name')->ignore($request->id)->whereNull('deleted_at')
            ],
            'amenity_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'amenity_type' => 'required',
        ];

        $messages = array(
            'amenity_name.required' => "The amenity name field is required.",
            'amenity_type.required' => "The amenity type field is required.",
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Amenity::find($request->id) : new Amenity;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Builder not found']);
        }

        $model->amenity_name = ucwords(strtolower(trim($request->amenity_name)));
        $model->amenity_type = $request->amenity_type;
        if ($request->hasFile('amenity_icon')) {
            if ($model->amenity_icon) {
                if (Storage::exists('public/amenity/icon/' . $model->amenity_icon)) {
                    Storage::delete('public/amenity/icon/' . $model->amenity_icon);
                }
            }
            $file = $request->file('amenity_icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/amenity/icon', $filename);
            $model->amenity_icon = $filename;
        }
        $model->status = $request->boolean('status', false);
        $model->updated_by = auth()->id();
        if (!$isUpdate) {
            $model->created_by = auth()->id();
        }

        if ($model->save()) {
            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $status = Amenity::$status;
        $amenityType = Amenity::$amenityType;
        $model = Amenity::find($request->id);
        if (isset($model->id)) {
            $model->amenity_icon_url = asset('storage/amenity/icon/' . $model->amenity_icon);
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d-m-Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d-m-Y g:i A', strtotime($model->updated_at)) : "";
            $model->updated_by_view = (isset($model->updatedBy->id)) ? $model->updatedBy->first_name . " " . $model->updatedBy->last_name : "";
            $model->amenity_type_name = (isset($amenityType[$model->amenity_type])) ? $amenityType[$model->amenity_type] : "";
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
        $model = Amenity::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
