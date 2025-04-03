<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ExioSuggest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExioSuggestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $section)
    {
        return view('backend.exio-suggest.index', compact('section'));
    }

    public function getSectionData(Request $request, $section)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = ExioSuggest::where('type', $section)->get();
        return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    
                    return '
                        <button class="btn btn-warning btn-sm edit-form" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#addModal">Edit</button>
                        <button class="btn btn-danger btn-sm delete-form" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->toJson();
                
    }

    public function sectionAddUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $isUpdate = (!empty($request->id) && $request->id) ? true : false;
       
        $rules = [            
            'title' => 'required|string|max:50|unique:exio_suggests,title,' . $request->id,
            'weightage' => 'required|numeric|min:1|max:100',
            'type' => 'required|string|in:section-a,section-b,section-c,section-d',
        ];
        
        $messages = [
            'type.in' => 'Invalid type provided. Allowed values: section-a, section-b, section-c, section-d.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $existingWeightage = ExioSuggest::where('id', '!=', $request->id)
                            ->where('type', $request->type)
                            ->sum('weightage');
    
        if (($existingWeightage + $request->weightage) > 100) {
            return response()->json([
                'status' => false,
                'errors' => ['weightage' => 'Total weightage should not exceed 100.']
            ]);
        }
        
        $model = $isUpdate ? ExioSuggest::find($request->id) : new ExioSuggest();

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Record not found']);
        }else{
            $model->type = $request->type;
        }
        
        $model->title = $request->title;
        $model->weightage = $request->weightage;
       
        if ($model->save()) {
            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $model = ExioSuggest::find($request->id);
        if (!$model) {
            return response()->json(['status' => false, 'message' => 'Data not found', 'data' => []], 404);
        }
        return response()->json($model);
    }

    public function delete(Request $request)
    {
        if(!isSuperAdmin()){
            $result = ['status' => false, 'message' => 'You do not have permission to delete this record.'];
            return response()->json($result);
        }

        $model = ExioSuggest::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
