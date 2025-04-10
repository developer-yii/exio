<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.setting.index');
    }

    public function get(Request $request)
    {
        $sqlQuery = Setting::query();

        return DataTables::eloquent($sqlQuery)
            ->editColumn('description', function ($row) {
                return ($row->description) ? \Illuminate\Support\Str::limit($row->description, 20) : "";
            })
            ->editColumn('updated_by', function ($row) {
                return (isset($row->updatedBy->id)) ? $row->updatedBy->name : "";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d.m.Y');
            })
            ->filter(function ($query) use ($request) {
                if ($filterDate = $request->get('filter_date')) {
                    if (strtotime($filterDate)) {
                        $formattedDate = date('Y-m-d', strtotime($filterDate));
                        $query->whereDate('settings.created_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('settings.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('settings.setting_label', 'LIKE', "%$searchValue%")
                            ->orWhere('settings.description', 'LIKE', "%$searchValue%");
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
            'setting_label' => [
                'required',
                'string',
                'max:100',
                Rule::unique('settings', 'setting_label')->ignore($request->id)->whereNull('deleted_at')
            ],
            'description' => 'required|string|max:500',
        ];

        if ($request->setting_key === 'check_match_video') {
            $rules['video'] = 'required|file|mimes:mp4,mov,avi|max:10240'; // Video required, max size 10MB
        } else {
            $rules['setting_value'] = 'required|string|max:500'; // Text value required
        }

        $messages = [
            'video.max' => 'The video file must not be larger than 10MB.',
            'video.mimes' => 'Only MP4, MOV, and AVI formats are allowed for the video.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Setting::find($request->id) : new Setting;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Setting not found']);
        }

        $isDefault = (isset($model->id) && $model->is_default == 1) ? true : false;

        if (!$isDefault) {
            $model->setting_key = Str::slug(strtolower($request->setting_label));
            $model->setting_label = $request->setting_label;
        }

        if ($request->setting_key === 'check_match_video') {
            if ($request->hasFile('video')) {
                if ($model->setting_value) {
                    if (Storage::exists('public/check-match-property-video/' . $model->setting_value)) {
                        Storage::delete('public/check-match-property-video/' . $model->setting_value);
                    }
                }
                $file = $request->file('video');
                $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/check-match-property-video', $filename);
                $model->setting_value = $filename;
            }
        }else{
            $model->setting_value = $request->setting_value;
        }


        $model->description = $request->description;
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
        $model = Setting::find($request->id);
        if (isset($model->id)) {
            $model->created_at_view =  ($model->created_at) ? date('d.m.Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d.m.Y g:i A', strtotime($model->updated_at)) : "";
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
}
