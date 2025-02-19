<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Builder;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Builder::$status;
        $cities = City::pluck('city_name', 'id');
        return view('backend.builder.index', compact('status', 'cities'));
    }

    public function get(Request $request)
    {
        $statusLabels = Builder::$status;

        $sqlQuery = Builder::select('builders.*')
            ->leftJoin('cities', 'builders.city_id', '=', 'cities.id')
            ->with('city:id,city_name');

        return DataTables::eloquent($sqlQuery)
            ->editColumn('builder_logo', function ($row) {
                return ($row->builder_logo) ? '<img src="' . asset('storage/builder/logo/' . $row->builder_logo) . '" alt="Builder Logo" style="width: 50px; height: 50px;">' : "";
            })
            ->addColumn('city_name', function ($row) {
                return $row->city->city_name ?? "";
            })
            ->editColumn('builder_about', function ($row) {
                return ($row->builder_about) ? \Illuminate\Support\Str::limit($row->builder_about, 20) : "";
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
                        $query->whereDate('builders.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('builders.status', $filterStatus);
                }

                if (($filterCity = $request->get('filter_city_id')) !== null) {
                    $query->where('builders.city_id', $filterCity);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('builders.builder_name', 'LIKE', "%$searchValue%")
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

        $rules = [
            'builder_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('builders', 'builder_name')->ignore($request->id)->whereNull('deleted_at')->where('city_id', $cityId)
            ],
            'builder_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'city_id' => 'required',
        ];

        $messages = array(
            'city_id.required' => "The city field is required.",
            'builder_about.regex' => "The builder about field is invalid.",
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Builder::find($request->id) : new Builder;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Builder not found']);
        }

        $model->city_id  = $request->city_id;
        $model->builder_name = ucwords(strtolower(trim($request->builder_name)));
        $model->builder_about = $request->builder_about;
        if ($request->hasFile('builder_logo')) {
            if ($model->builder_logo) {
                if (Storage::exists('public/builder/logo/' . $model->builder_logo)) {
                    Storage::delete('public/builder/logo/' . $model->builder_logo);
                }
            }
            $file = $request->file('builder_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/builder/logo', $filename);
            $model->builder_logo = $filename;
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
        $status = Builder::$status;
        $model = Builder::find($request->id);
        if (isset($model->id)) {
            $model->builder_logo_url = asset('storage/builder/logo/' . $model->builder_logo);
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d-m-Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d-m-Y g:i A', strtotime($model->updated_at)) : "";
            $model->updated_by_view = (isset($model->updatedBy->id)) ? $model->updatedBy->name : "";
            $model->city_name = (isset($model->city->id)) ? $model->city->city_name : "";
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
        $model = Builder::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
