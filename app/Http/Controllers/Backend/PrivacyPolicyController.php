<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $model = PrivacyPolicy::first();
        return view('backend.privacy_policie.index', compact('model'));
    }

    public function addupdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $model = PrivacyPolicy::first();
        if (!$model) {
            $model = new PrivacyPolicy;
            $model->created_by = auth()->id();
        }

        $model->title = $request->title;
        $model->content = $request->content;
        $model->updated_by = auth()->id();

        if ($model->save()) {
            return response()->json(['status' => true, 'message' => "Data updated successfully"]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }
}
