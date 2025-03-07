<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DownloadBrochure;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DownloadBrochureDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.download-brochure.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = DownloadBrochure::with('project');
        return DataTables::of($data)
                ->filterColumn('project_name', function ($query, $keyword) {
                    $query->whereHas('project', function ($query) use ($keyword) {
                        $query->where('project_name', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('project_name', function ($row) {
                    return $row->project ? $row->project->project_name : 'N/A';
                })
                ->rawColumns(['project_name'])
                ->toJson();
    }
}
