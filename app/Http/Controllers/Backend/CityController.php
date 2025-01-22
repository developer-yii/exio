<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\City;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = City::$status;
        return view('backend.city.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = City::$status;

        $sqlQuery = City::query();

        return DataTables::eloquent($sqlQuery)
            ->addColumn('status_text', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? "";
            })
            ->editColumn('updated_by', function ($row) {
                return (isset($row->updatedBy->id)) ? $row->updatedBy->first_name . " " . $row->updatedBy->last_name : "";
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d.m.Y');
            })
            ->filter(function ($query) use ($request) {
                if ($filterDate = $request->get('filter_date')) {
                    if (strtotime($filterDate)) {
                        $formattedDate = date('Y-m-d', strtotime($filterDate));
                        $query->whereDate('cities.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('cities.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('cities.city_name', 'LIKE', "%$searchValue%");
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
            'city_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cities', 'city_name')->ignore($request->id)->whereNull('deleted_at')
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? City::find($request->id) : new City;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'City not found']);
        }

        $model->city_name = ucwords(strtolower(trim($request->city_name)));
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
        $status = City::$status;
        $model = City::find($request->id);
        if (isset($model->id)) {
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d.m.Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d.m.Y g:i A', strtotime($model->updated_at)) : "";
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
        $model = City::where('id', $request->id)->first();
        if ($model) {
            $model->locations()->delete();
            $model->delete();
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
