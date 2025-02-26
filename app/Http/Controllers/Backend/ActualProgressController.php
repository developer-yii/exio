<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ActualProgress;
use App\Models\ActualProgressImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ActualProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($project_id)
    {
        $project = Project::find($project_id);
        $statuses = ActualProgress::$status;
        return view('backend.actual-progress.index', compact('project', 'statuses'));
    }

    public function get(Request $request)
    {
        $sqlQuery = ActualProgress::select('actual_progress.*')
            ->with(['updatedBy'])
            ->where('actual_progress.project_id', $request->project_id);

        return DataTables::eloquent($sqlQuery)
            ->editColumn('timeline', function ($row) {
                return $row->timeline . ' Months';
            })
            ->editColumn('work_completed', function ($row) {
                return $row->work_completed . '%';
            })
            ->editColumn('status_text', function ($row) {
                return ActualProgress::$status[$row->status];
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
                        $query->whereDate('actual_progress.updated_at', $formattedDate);
                    }
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('actual_progress.timeline', 'LIKE', "%$searchValue%")
                            ->orWhere('actual_progress.work_completed', 'LIKE', "%$searchValue%");
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
            'date' => 'required|date',
            'project_id' => 'required|integer',
            'timeline' => [
                'integer',
                'string',
                'min:0',
            ],
            'work_completed' => 'required|integer|min:0|max:100',
            'status' => 'required|in:0,1',
        ];

        $messages = array(
            'timeline.required' => 'Timeline is required',
            'timeline.string' => 'Timeline must be a string',
            'timeline.min' => 'Timeline must be at least 0',
            'work_completed.required' => 'Work completed is required',
            'work_completed.string' => 'Work completed must be a string',
            'work_completed.min' => 'Work completed must be at least 0',
            'work_completed.max' => 'Work completed may not be greater than 100',
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a date',
            'project_id.required' => 'Project is required',
            'project_id.integer' => 'Project must be an integer',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? ActualProgress::find($request->id) : new ActualProgress;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'ActualProgress not found']);
        }

        $model->project_id = $request->project_id;
        $model->date = date('Y-m-d', strtotime($request->date));
        $model->timeline = $request->timeline;
        $model->work_completed = $request->work_completed;
        $model->description = $request->description;
        $model->status = $request->status;
        $model->updated_by = auth()->id();
        if (!$isUpdate) {
            $model->created_by = auth()->id();
        }

        if ($model->save()) {
            // Save actualProgress images
            $existingActualProgressImagesIds = ActualProgressImage::where('actual_progress_id', $model->id)
                ->pluck('id')
                ->toArray();

            $updatedActualProgressImagesIds = [];

            foreach ($request->actual_progress_images as $index => $images) {
                $actualProgressImages = $images['id'] ? ActualProgressImage::find($images['id']) : new ActualProgressImage;
                $actualProgressImages->actual_progress_id = $model->id;

                if ($request->hasFile('actual_progress_images.' . $index . '.image')) {
                    $file = $request->file('actual_progress_images.' . $index . '.image');
                    $fileName = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/actual_progress_images', $fileName);
                    $actualProgressImages->image = $fileName;
                }
                $actualProgressImages->save();

                $updatedActualProgressImagesIds[] = $actualProgressImages->id;
            }

            $idsToDelete = array_diff($existingActualProgressImagesIds, $updatedActualProgressImagesIds);
            if (!empty($idsToDelete)) {
                ActualProgressImage::whereIn('id', $idsToDelete)->delete();
            }

            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $status = ActualProgress::$status;
        $model = ActualProgress::with('images')->find($request->id);
        if (isset($model->id)) {
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
        $model = ActualProgress::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }

    public function getImages(Request $request)
    {
        $model = ActualProgress::with('images')->where('id', $request->actual_progress_id)->first();
        if ($model) {
            $result = ['status' => true, 'message' => '', 'data' => $model->images];
        } else {
            $result = ['status' => false, 'message' => 'Invalid request', 'data' => []];
        }
        return response()->json($result);
    }
}
