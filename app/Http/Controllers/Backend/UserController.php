<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\Facades\DataTables;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = User::$status;
        return view('backend.user.index', compact('status'));
    }

    public function get(Request $request)
    {
        $statusLabels = User::$status;

        $sqlQuery = User::where('users.role_type', User::USER);

        return DataTables::eloquent($sqlQuery)
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
                        $query->whereDate('users.created_at', $formattedDate);
                    }
                }

                if (($filterStatus = $request->get('filter_status')) !== null) {
                    $query->where('users.status', $filterStatus);
                }

                if ($searchValue = $request->get('search')['value'] ?? null) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->orWhere('users.first_name', 'LIKE', "%$searchValue%")
                            ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                            ->orWhere('users.mobile', 'LIKE', "%$searchValue%")
                            ->orWhere('users.email', 'LIKE', "%$searchValue%");
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
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($request->id)->whereNull('deleted_at')
            ],
            'mobile' => 'nullable|bail|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:13',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'password' => $isUpdate ? 'nullable|same:confirm_password|min:8' : 'required|same:confirm_password|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = $isUpdate ? User::find($request->id) : new User;

        if ($isUpdate && !$model) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->email = $request->email;
        $model->mobile =  $request->filled('mobile') ? str_replace(' ', '', trim($request->mobile)) : null;
        $model->status = $request->boolean('status', false);
        $model->role_type = User::USER;

        if ($request->filled('password')) {
            $model->password = Hash::make($request->password);
        }

        if (!$isUpdate && !$model->email_verified_at) {
            $model->email_verified_at = Carbon::now();
        }

        if ($model->save()) {
            $message = $isUpdate ? 'Data updated successfully' : 'Data added successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    public function detail(Request $request)
    {
        $status = User::$status;
        $role = User::$role;

        $model = User::find($request->id);
        if (isset($model->id)) {
            $model->status_text =  (isset($status[$model->status])) ? $status[$model->status] : "";
            $model->role_type_text =  (isset($role[$model->role_type])) ? $role[$model->role_type] : "";
            $model->created_at_view =  ($model->created_at) ? date('d.m.Y g:i A', strtotime($model->created_at)) : "";
            $model->updated_at_view =  ($model->updated_at) ? date('d.m.Y g:i A', strtotime($model->updated_at)) : "";
            $model->email_verified_at_view =  ($model->email_verified_at) ? date('d.m.Y g:i A', strtotime($model->email_verified_at)) : "";

            $result = ['status' => true, 'message' => '', 'data' => $model];
        } else {
            $result = ['status' => false, 'message' => 'Invalid request', 'data' => []];
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $model = User::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }

    public function profile()
    {
        $userId = auth()->id();
        $loginUser = User::find($userId);
        if (!$loginUser) {
            return redirect()->route('admin.login');
        }
        return view('backend.profile.index', compact('loginUser'));
    }

    public function profileupdate(Request $request)
    {
        $userId = auth()->id();

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'nullable|bail|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:13',
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at'),
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = User::find($userId);

        if (!$model) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->email = $request->email;
        $model->mobile =  $request->filled('mobile') ? str_replace(' ', '', trim($request->mobile)) : null;

        if ($model->save()) {
            return response()->json(['status' => true, 'message' => 'Profile successfully updated']);
        }

        return response()->json(['status' => false, 'message' => 'Error saving profile data']);
    }

    public function updatepassword(Request $request)
    {
        $userId = auth()->id();

        $rules = [
            'current_password' => ['required'],
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:new_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = User::find($userId);

        if (!$model) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json(['status' => false, 'message' => 'The provided current password is incorrect.']);
        }

        $model->password = Hash::make($request->new_password);

        if ($model->save()) {
            return response()->json(['status' => true, 'message' => 'Password updated successfully']);
        }

        return response()->json(['status' => false, 'message' => 'Error updating password']);
    }
}
