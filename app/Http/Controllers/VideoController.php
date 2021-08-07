<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;
use App\Models\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic  as Image;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'incrementViews']);
    }

    public function mostViewedVideos ()
    {
        $views = View::orderBy('views_number', 'DESC')
        ->take(10)
        ->get(['user_id', 'video_id', 'views_number']);

        $videoNames = [];
        $videoViews = [];

        foreach ($views as $view) {
            array_push($videoNames, $view->video->title);
            array_push($videoViews, $view->views_number);
        }

        return view('admin.most-viewed-videos', compact('views'))
        ->with('videoViews', json_encode($videoViews, JSON_NUMERIC_CHECK))
        ->with('videoNames', json_encode($videoNames, JSON_NUMERIC_CHECK));
    }

    public function incrementViews (Request $request) 
    {
        $views = View::where('video_id', $request->videoId)->first();
        $views->increment('views_number');

        return response()->json(['viewsNumber' => $views->views_number]);
    }

    public function index(Request $request)
    {
        $videos = auth()->user()->videos->sortByDesc('created_at');
        $title = 'أحدث فيديوهاتي';

        return view('videos.my-videos', compact('videos', 'title'));
    }

    public function create()
    {
        return view('videos.uploader');
    }

    public function store(Request $request)
    {

        request()->validate([
            'title' => 'required',
            'image' => 'required',
            'video' => 'required'
        ]);

        $randomPath = Str::random(16);

        $videoPath = $randomPath . '.' . $request->video->getClientOriginalExtension();
        $imagePath = $randomPath . '.' . $request->image->getClientOriginalExtension();

        $image = Image::make($request->image)->resize(320, 180);
        Storage::put($imagePath, $image->stream());

        $request->video->storeAs('/', $videoPath, 'public');

        $video = Video::create([
            'disk' => 'public',
            'video_path' => $videoPath,
            'image_path' => $imagePath,
            'title' => $request->title,
            'user_id' => auth()->id()
        ]);

        View::create([
            'video_id' => $video->id,
            'user_id' => auth()->id(),
            'views_number' => 0
        ]);

        ConvertVideoForStreaming::dispatch($video);

        return redirect()->back()->with(
            'success',
            'سيكون المقطع متوفرا بعد الإنتهاء من معالجته'
        );
    }

    public function search(Request $request)
    {
        $videos = Video::where('title', 'LIKE', "%{$request->term}%")->paginate(12);
        $title = "عرض نتائج البحث عن :  {$request->term}";

        return view('videos.my-videos', compact('videos', 'title'));
    }

    public function show(Video $video)
    {
        $countLikes = $video->likes()->where('like', 1)->count();
        $countDislikes = $video->likes()->where('like', 0)->count();
        // $views = ++$video->view;
    
        $userLike = 0;
        if (auth()->user()) {
            $userLike = auth()->user()->likes()->where('video_id', $video->id)->first();
        }

        $comments = $video->comments->sortByDesc('created_at');

        if (Auth::check()) {
            auth()->user()->videoInHistory()->attach($video->id);
        }

        return view('videos.show-video', compact('video', 'countLikes', 'countDislikes', 'userLike', 'comments'));
    }

    public function edit(Video $video)
    {
        return view('videos.edit-video', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = request()->validate([
            'title' => 'required',
            'image' => 'sometimes'
        ]);

        if ($request->hasFile('image')) {
            Storage::delete($video->image_path);

            $randomPath = Str::random(16);
            $imagePath = $randomPath . '.' . $request->image->getClientOriginalExtension();
            $image = Image::make($request->image)->resize(320, 180);
            Storage::put($imagePath, $image->stream());

            $data['image_path'] = $imagePath;
        }

        $video->update($data);
        return back()->with('success', 'تم تعديل الفيديو بنجاح');
    }

    public function destroy(Video $video)
    {
        $converedVideos = $video->convertedVideos;
        foreach ($converedVideos as $converedVideo) {
            Storage::delete([
                $converedVideo->mp4_Format_240,
                $converedVideo->mp4_Format_360,
                $converedVideo->mp4_Format_480,
                $converedVideo->mp4_Format_720,
                $converedVideo->mp4_Format_1080,

                $converedVideo->webm_Format_240,
                $converedVideo->webm_Format_360,
                $converedVideo->webm_Format_480,
                $converedVideo->webm_Format_720,
                $converedVideo->webm_Format_1080,

                $video->image_path,
                $video->path
            ]);
        }

        $video->delete();
        return back()->with('success', 'تم حذف الفيديو بنجاح');
    }
}
