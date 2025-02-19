<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class LocalityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Locality::$status;
        return view('backend.locality.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = Locality::$status;

        $sqlQuery = Locality::select('localities.*');

        return DataTables::eloquent($sqlQuery)
            ->editColumn('locality_image', function ($row) {
                return ($row->locality_image) ? '<img src="' . asset('storage/locality/image/' . $row->locality_image) . '" alt="Locality Image" style="width: 50px; height: 50px;">' : "";
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
                        $query->whereDate('localities.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('localities.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('localities.locality_name', 'LIKE', "%$searchValue%");
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

        $rules = [
            'locality_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('localities', 'locality_name')->ignore($request->id)->whereNull('deleted_at')
            ],
            'locality_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];

        $messages = array(
            'locality_name.required' => "The locality name field is required.",
            'locality_image.image' => "The locality image must be an image.",
            'locality_image.mimes' => "The locality image must be a file of type: jpeg, png, jpg, gif, svg.",
            'locality_image.max' => "The locality image may not be greater than 5MB.",
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Locality::find($request->id) : new Locality;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Locality not found']);
        }

        $model->locality_name = ucwords(strtolower(trim($request->locality_name)));
        if ($request->hasFile('locality_image')) {
            if ($model->locality_image) {
                if (Storage::exists('public/locality/image/' . $model->locality_image)) {
                    Storage::delete('public/locality/image/' . $model->locality_image);
                }
            }
            $file = $request->file('locality_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/locality/image', $filename);
            $model->locality_image = $filename;
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
        $status = Locality::$status;
        $model = Locality::find($request->id);
        if (isset($model->id)) {
            $model->locality_image_url = asset('storage/locality/image/' . $model->locality_image);
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
        $model = Locality::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
