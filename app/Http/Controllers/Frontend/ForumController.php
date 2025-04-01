<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ForumNotificationEmail;
use App\Models\Forum;
use App\Models\ForumAnswer;
use App\Rules\ReCaptcha;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    public function forumList(Request $request){
        // $forums = Forum::with(['user', 'answers'])->where('status', 1)->get();

        $query = Forum::with([
            'user',
            'answers' => function ($query) {
                $query->where('status', 1);
            }
        ])->where('status', 1);

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('question', 'LIKE', $searchTerm)
                    ->orWhereHas('answers', function ($query) use ($searchTerm) {
                        $query->where('answer', 'LIKE', $searchTerm);
                    });
            });
        }

        $forums = $query->orderBy('id', 'desc')->paginate(5);

        $topForums = Forum::with([
            'user',
            'answers' => function ($query) {
                $query->where('status', 1)->with('user');
            }
        ])
        ->where('status', 1)
        ->withCount(['answers' => function ($query) {
            $query->where('status', 1);
        }])
        ->orderByDesc('answers_count')
        ->get()->take(7);
        return view('frontend.forum.index', compact('forums', 'topForums'));
    }

    public function forumDetails(Request $request, $id){
        $forum = Forum::with([
            'user',
            'answers' => function ($query) {
                $query->where('status', 1)->with('user');
            }
        ])
        ->where('status', 1)
        ->where('id', $id)->first();

        if(!$forum){
            abort('404');
        }

        return view('frontend.forum.details', compact('forum'));
    }

    public function questionSubmit(Request $request){
        try {
            $validatedData = Validator::make($request->all(),[
                'question' => 'required',
                'g-recaptcha-response' => ['required', new ReCaptcha],
            ]);

            if ($validatedData->fails()){
                $response = ['status' => false,'errors' => $validatedData->errors()];
                return response()->json($response);
            }

            // Forum::create([
            //     'question' => $request->input('question'),
            //     'user_id' => Auth::id(),
            // ]);

            $forum = Forum::create([
                'question' => $request->input('question'),
                'user_id' => Auth::id(),
            ]);
    
            // Email data
            $data = [
                'type' => 'New Question',
                'content' => $forum->question,
                'user_name' => Auth::user()->name,
            ];
    
            // Send email
            Mail::to(env('FORUM_MAIL'))->send(new ForumNotificationEmail($data));

            return response()->json(['status' => true, 'message' => 'Question submitted successfully!']);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function answerSubmit(Request $request){
        try {
            $validatedData = Validator::make($request->all(),[
                'forum-id' => 'required|exists:forums,id',
                'answer' => 'required',
                'g-recaptcha-response' => ['required', new ReCaptcha],
            ]);

            if ($validatedData->fails()){
                $response = ['status' => false,'errors' => $validatedData->errors()];
                return response()->json($response);
            }

            $answer = ForumAnswer::create([
                'forum_id' => $request->input('forum-id'),
                'answer' => $request->input('answer'),
                'user_id' => Auth::id(),
            ]);
    
            // Email data
            $data = [
                'forum_id' => $request->input('forum-id'),
                'type' => 'New Answer',
                'content' => $answer->answer,
                'user_name' => Auth::user()->name,
            ];
    
            // Send email
            Mail::to(env('FORUM_MAIL'))->send(new ForumNotificationEmail($data));

            return response()->json(['status' => true, 'message' => 'Answer submitted successfully!']);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
