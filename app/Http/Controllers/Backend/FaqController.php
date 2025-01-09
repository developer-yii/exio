<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Faq;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Faq::$status;
        return view('backend.faq.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = Faq::$status;

        $sqlQuery = Faq::query();

        return DataTables::eloquent($sqlQuery)
            ->editColumn('answer', function ($row) {
                return ($row->answer) ? \Illuminate\Support\Str::limit($row->answer, 20) : "";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d.m.Y');
            })
            ->addColumn('status_text', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? "";
            })
            ->filter(function ($query) use ($request) {
                if ($filterDate = $request->get('filter_date')) {
                    if (strtotime($filterDate)) {
                        $formattedDate = date('Y-m-d', strtotime($filterDate));
                        $query->whereDate('faqs.created_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('faqs.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('faqs.question', 'LIKE', "%$searchValue%")
                            ->orWhere('faqs.answer', 'LIKE', "%$searchValue%")
                            ->orWhere('faqs.order_index', 'LIKE', "%$searchValue%");
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
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order_index' => 'bail|required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? Faq::find($request->id) : new Faq;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'Faq not found']);
        }

        $model->question = $request->question;
        $model->answer = $request->answer;
        $model->order_index = (int)$request->order_index;
        $model->status = $request->boolean('status', false);
        $model->updated_by = auth()->id();
        if(!$isUpdate){
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
        $status = Faq::$status;

        $model = Faq::find($request->id);
        if (isset($model->id)) {
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->created_at_view =  ($model->created_at) ? date('d.m.Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d.m.Y g:i A', strtotime($model->updated_at)) : "";

            $result = ['status' => true, 'message' => '', 'data' => $model];
        } else {
            $result = ['status' => false, 'message' => 'Invalid request', 'data' => []];
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $model = Faq::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
}
