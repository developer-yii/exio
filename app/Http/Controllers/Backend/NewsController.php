<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\News;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = News::$status;
        return view('backend.news.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = News::$status;

        $sqlQuery = News::select('news.*');

        return DataTables::eloquent($sqlQuery)
            ->addColumn('image', function ($row) {
                $image = asset('storage/news/image/' . $row->image);
                return '<img src="' . $image . '" class="img-fluid" style="max-height: 50px; width: 100px;">';
            })
            ->editColumn('description', function ($row) {
                return ($row->description) ? \Illuminate\Support\Str::limit($row->description, 20) : "";
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
                        $query->whereDate('news.updated_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('news.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('news.title', 'LIKE', "%$searchValue%")
                            ->orWhere('news.slug', 'LIKE', "%$searchValue%")
                            ->orWhere('news.description', 'LIKE', "%$searchValue%");
                    });
                }
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function add()
    {
        $status = News::$status;
        return view('backend.news.addupdate', compact('status'));
    }

    public function edit($id)
    {
        $model = News::find($id);
        if ($model) {
            $status = News::$status;
            return view('backend.news.addupdate', compact('model', 'status'));
        } else {
            return redirect()->route('admin.news.index')->with('error', 'News not found');
        }
    }

    public function addupdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $isUpdate = (!empty($request->id) && $request->id) ? true : false;

        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('news', 'slug')->ignore($request->id)->whereNull('deleted_at'),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ],
            'description' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'status' => 'required|in:0,1',
            'added_by' => 'required|string|max:255',
        ];

        $messages = array(
            'title.required' => "The title field is required.",
            'slug.required' => "The slug field is required.",
            'description.required' => "The description field is required.",
            'content.required' => "The content field is required.",
            'image.image' => "The image must be an image.",
            'image.mimes' => "The image must be a file of type: jpeg, png, jpg, gif, svg.",
            'image.max' => "The image may not be greater than 5MB.",
            'status.required' => "The status field is required.",
            'status.in' => "The status field must be 0 or 1.",
            'added_by.required' => "The added by field is required.",
            'added_by.string' => "The added by field must be a string.",
            'added_by.max' => "The added by field must be less than 255 characters.",
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? News::find($request->id) : new News;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'News not found']);
        }

        $slug = $request->slug;
        if (empty($slug)) {
            $slug = Str::slug($request->title);
        }

        if (News::where('slug', $slug)->where('id', '!=', $request->id)->exists()) {
            $slug = $slug . '-' . time();
        }

        $model->title = $request->title;
        $model->slug = $slug;
        if ($request->hasFile('image')) {
            if ($model->image) {
                if (Storage::exists('public/news/image/' . $model->image)) {
                    Storage::delete('public/news/image/' . $model->image);
                }
            }
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/news/image', $filename);
            $model->image = $filename;
        }
        $model->description = $request->description;
        $model->content = $request->content;
        $model->status = $request->boolean('status', false);
        $model->added_by = $request->added_by;
        $model->updated_by = auth()->id();
        if (!$isUpdate) {
            $model->created_by = auth()->id();
            $model->views = 0;
        }

        if ($model->save()) {
            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $status = News::$status;
        $model = News::find($request->id);
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
        $model = News::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }

}
