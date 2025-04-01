<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumAnswer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ForumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $status = Forum::$status;
        return view('backend.forum.index', compact('status'));
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = Forum::with('user')->select('forums.*');
        return DataTables::of($data)
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = route('admin.forum.answer', ['id' => $row->id]);
                
                    return '
                        <a class="btn btn-primary btn-sm view-answer" href="' . $viewUrl . '">View Answer</a>
                        <button class="btn btn-warning btn-sm edit-forum" data-id="' . $row->id . '" data-question="' . htmlspecialchars($row->question) . '" data-status="' . $row->status . '">Edit</button>
                        <button class="btn btn-danger btn-sm delete-forum" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->toJson();
                
    }

    public function forumUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $request->validate([
            'question' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        $forum = Forum::findOrFail($request->id);
        $forum->question = $request->question;
        $forum->status = $request->status;
        $forum->save();

        if ($forum->save()) {
            $message = 'Forum question updated successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
        
    }

    public function forumDelete(Request $request)
    {
        if(!isSuperAdmin()){
            $result = ['status' => false, 'message' => 'You do not have permission to delete this forum.'];
            return response()->json($result);
        }

        $model = Forum::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }
   
    public function answers(Request $request,$id)
    {
        $status = Forum::$status;
        $question = Forum::where('id', $id)->value('question');
        return view('backend.forum.answers', compact('id', 'question', 'status'));
    }

    public function getAnswers($id)
    {
        $answers = ForumAnswer::where('forum_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc');

        return DataTables::of($answers)
            ->addColumn('user', function ($answer) {
                return $answer->user->name;
            })
            ->addColumn('action', function ($row) {
            
                return '
                    <button class="btn btn-warning btn-sm edit-forum" data-id="' . $row->id . '" data-answer="' . htmlspecialchars($row->answer) . '" data-status="' . $row->status . '">Edit</button>
                    <button class="btn btn-danger btn-sm delete-forum" data-id="' . $row->id . '">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function answerUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => false, 'message' => 'Invalid request'], 400);
        }

        $request->validate([
            'answer' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        $forum = ForumAnswer::findOrFail($request->id);
        $forum->answer = $request->answer;
        $forum->status = $request->status;
        $forum->save();

        if ($forum->save()) {
            $message = 'Forum answer updated successfully';
            return response()->json(['status' => true, 'message' => $message]);
        }

        return response()->json(['status' => false, 'message' => 'Error saving data']);
        
    }

    public function answerDelete(Request $request)
    {
        if(!isSuperAdmin()){
            $result = ['status' => false, 'message' => 'You do not have permission to delete this forum answer.'];
            return response()->json($result);
        }

        $model = ForumAnswer::where('id', $request->id)->first();
        if ($model && $model->delete()) {
            $result = ['status' => true, 'message' => 'Record deleted successfully'];
        } else {
            $result = ['status' => false, 'message' => 'Something went wrong'];
        }
        return response()->json($result);
    }

}
