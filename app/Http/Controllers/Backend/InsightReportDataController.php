<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InsightsReportDownload;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InsightReportDataController extends Controller
{
    public function index()
    {
        return view('backend.insight-reports.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = InsightsReportDownload::with([
            'user:id,name,email,mobile',
            'property:id,project_name'
        ])
        ->select('insights_report_downloads.*');

        return DataTables::of($data)
            ->filterColumn('project_name', function ($query, $keyword) {
                $query->whereHas('property', function ($query) use ($keyword) {
                    $query->where('project_name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(insights_report_downloads.created_at, '%d-%m-%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->getRawOriginal('created_at'))->format('d-m-Y');
            })
            ->addColumn('project_name', function ($row) {
                return $row->property ? $row->property->project_name : 'N/A';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('user_email', function ($row) {
                return $row->user ? $row->user->email : 'N/A';
            })
            ->addColumn('user_mobile', function ($row) {
                return ($row->user && !empty($row->user->mobile)) ? $row->user->mobile : 'N/A';
            })
            ->rawColumns(['project_name', 'user_name'])
            ->toJson();
    }
}
