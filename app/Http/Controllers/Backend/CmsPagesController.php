<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CmsPages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CmsPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $page_name) {
        $page = $request->page_name;
        $page = CmsPages::where('page_name', $page)->first();
        if (!$page) {
            abort(404);
        }
        return view('backend.cmspages.index', compact('page'));
    }

    public function update(Request $request)
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

        $model = CmsPages::find($request->page);
        if (!$model) {
            return response()->json(['status' => false, 'message' => 'Page not Found'], 400);
        }

        $model->title = $request->title;
        $model->content = $request->content;
        $model->updated_by = auth()->id();

        if ($model->save()) {
            return response()->json(['status' => true, 'message' => "Data updated successfully"]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
    }

    // public function imageUpload(Request $request)
    // {
    //     $image = $request->file('upload');

    //     $dir = storage_path("app/public/cmspages/");

    //     // Create the directory if it does not exist with the correct permissions
    //     if (!is_dir($dir)) {
    //         mkdir($dir, 0755, true); // Creates the directory with 0755 permissions recursively
    //     }

    //     $fileName    = uniqid() . '.' . $image->getClientOriginalExtension();
    //     $filePath = $dir . '/' . $fileName;

    //     // $path = Storage::disk('local')->put($filePath, File::get($image));
    //     $fileContent = file_get_contents(($image));
    //     // pr($fileContent);
    //     $path = Storage::disk('local')->put($filePath, $fileContent, ['visibility' => 'public']);

    //     if ($path) {
    //         $url = Storage::disk('local')->url($filePath);
    //     } else {
    //         return response()->json([
    //             'uploaded' => false,
    //             'error'    => [
    //                 'message' => 'Failed to upload image.',
    //             ],
    //         ]);
    //     }
    //     $CKEditorFuncNum = $request->input('CKEditorFuncNum');
    //     $response        = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>";
    //     echo $response;
    // }


}
