<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.subscriber.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = Subscriber::query();
        return DataTables::of($data)
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->toJson();
    }
}
