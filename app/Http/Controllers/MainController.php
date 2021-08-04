<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index ()
    {
        $date = Carbon::today()->subDays(7);
        $videos = Video::join('views', 'videos.id', 'views.video_id')
                         ->orderBy('views.views_number', 'DESC')
                         ->where('videos.created_at', '>=', $date)
                         ->take(16)
                         ->get('videos.*');
        $title = 'أكثر الفيديوهات مشاهدة لهذا الأسبوع';
        return view('main', compact('videos', 'title'));
    }

    public function showChannelVideos (User $channel) 
    {
        $videos = $channel->videos;

        $title = 'فيديوهات قناة : ' . $channel->name ;
        return view('videos.my-videos', compact('videos', 'title')); 
    }
}
