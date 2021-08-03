<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');    
    }

    public function store (Request $request)
    {
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'body' => $request->comment,
            'video_id' => $request->videoId
        ]);

        $userName = auth()->user()->name;
        $userImage = auth()->user()->profile_photo_url;
        $commentDate = Carbon::now()->diffForHumans();
        $commentId = $comment->id;

        return response()->json([
            'userName' => $userName,
            'userImage' => $userImage,
            'commentDate'=> $commentDate,
            'commentId' => $commentId
        ]);
    }

    public function destroy (Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'حُذف التعليق');
    }

    public function edit (Comment $comment)
    {
        return view('edit-comment', compact('comment'));
    }

    public function update (Request $request, Comment $comment)
    {
        request()->validate([
            'comment' => 'required'
        ]);

        $comment->body = $request->comment;
        $comment->update();

        return redirect('/videos/' . $comment->video_id)->with('success', 'عُدل التعليق');
    }
}
