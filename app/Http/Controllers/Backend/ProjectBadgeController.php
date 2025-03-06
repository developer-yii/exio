<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProjectBadge;
use Illuminate\Support\Str;

class ProjectBadgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = ProjectBadge::$status;
        return view('backend.project_badge.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = ProjectBadge::$status;

        $sqlQuery = ProjectBadge::select('project_badges.*')
            ->groupBy('project_badges.id');

        return DataTables::eloquent($sqlQuery)
            ->addColumn('status_text', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? "";
            })
            ->editColumn('updated_by', function ($row) {
                return (isset($row->updatedBy->id)) ? $row->updatedBy->name : "";
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d.m.Y');
            })
            ->filter(function ($query) use ($request) {
                if ($filterDate = $request->get('filter_date')) {
                    if (strtotime($filterDate)) {
                        $formattedDate = date('Y-m-d', strtotime($filterDate));
                        $query->whereDate('project_badges.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('project_badges.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('project_badges.name', 'LIKE', "%$searchValue%");
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
            'name' => [
                'required',
                'string',
                'max:100',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? ProjectBadge::find($request->id) : new ProjectBadge;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Project Badge not found']);
        }

        $model->name = trim($request->name);
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (ProjectBadge::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $model->slug = $slug;
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
        $status = ProjectBadge::$status;
        $model = ProjectBadge::find($request->id);
        if (isset($model->id)) {
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d.m.Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d.m.Y g:i A', strtotime($model->updated_at)) : "";
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
        $model = ProjectBadge::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
