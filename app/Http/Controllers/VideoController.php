<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic  as Image;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $path = Storage::put($imagePath, $image->stream());

        $request->video->storeAs('/', $videoPath, 'public');

        $video = Video::create([
            'disk' => 'public',
            'video_path' => $videoPath,
            'image_path' => $imagePath,
            'title' => $request->title,
            'user_id' => auth()->id()
        ]);

        ConvertVideoForStreaming::dispatch($video);
        
        return redirect()->back()->with(
            'success',
            'سيكون المقطع متوفرا بعد الإنتهاء من معالجته'
        );
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Video $video)
    {
        $converedVideos = $video->convertedVideos;
        foreach ($converedVideos as $converedVideo)
        {
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
