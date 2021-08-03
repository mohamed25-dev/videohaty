<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Video;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function likeVideo (Request $request) 
    {
        $videoId = $request->videoId;
        $isLike = $request->isLike == 'true';
        $update = false;

        $video = Video::find($videoId);
        if (!$video) {
            return null;
        }

        $user = auth()->user();
        $like = $user->likes()->where('video_id', $videoId)->first();
        if ($like) {
            $alreadyLike = $like->like;
            $update = true;
            if ($alreadyLike == $isLike) {
                $like->delete();
            } 
        } else {
            $like = new Like();
        }

        $like->like = $isLike;
        $like->user_id = auth()->id();
        $like->video_id = $videoId;

        if ($update) {
            $like->update();
        } else {
            $like->save();
        }

        $countLikes = $video->likes()->where('like', 1)->count();
        $countDislikes = $video->likes()->where('like', 0)->count();

        return response()->json([
            'countLikes' => $countLikes,
            'countDislikes' => $countDislikes,
        ]);
    }
}
