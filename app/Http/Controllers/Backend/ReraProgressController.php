<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ReraProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ReraProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($project_id)
    {
        $project = Project::find($project_id);
        return view('backend.rera-progress.index', compact('project'));
    }

    public function get(Request $request)
    {
        $sqlQuery = ReraProgress::select('rera_progress.*')
            ->with(['updatedBy'])
            ->where('rera_progress.project_id', $request->project_id);

        return DataTables::eloquent($sqlQuery)
            ->editColumn('timeline', function ($row) {
                return $row->timeline . ' Months';
            })
            ->editColumn('work_completed', function ($row) {
                return $row->work_completed . '%';
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
                        $query->whereDate('rera_progress.updated_at', $formattedDate);
                    }
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('rera_progress.timeline', 'LIKE', "%$searchValue%")
                            ->orWhere('rera_progress.work_completed', 'LIKE', "%$searchValue%");
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
            'timeline' => [
                'integer',
                'string',
                'min:0',
            ],
            'work_completed' => 'required|integer|min:0|max:100',
        ];

        $messages = array(
            'timeline.required' => 'Timeline is required',
            'timeline.string' => 'Timeline must be a string',
            'timeline.min' => 'Timeline must be at least 0',
            'work_completed.required' => 'Work completed is required',
            'work_completed.string' => 'Work completed must be a string',
            'work_completed.min' => 'Work completed must be at least 0',
            'work_completed.max' => 'Work completed may not be greater than 100',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? ReraProgress::find($request->id) : new ReraProgress;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'ReraProgress not found']);
        }

        $model->project_id = $request->project_id;
        $model->timeline = $request->timeline;
        $model->work_completed = $request->work_completed;
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
        $model = ReraProgress::find($request->id);
        if (isset($model->id)) {
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
        $model = ReraProgress::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
