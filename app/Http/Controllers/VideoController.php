<?php

namespace App\Http\Controllers;

use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic  as Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('videos.uploader');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        // return die($videoPath);

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

        $lowBitRateFromat = (new X264('aac', 'libx264'))->setKiloBitrate(400);
        $low2_BitRatFromat = (new X264('aac', 'libx264'))->setKiloBitrate(800);
        $mediumBitRateFromat = (new X264('aac', 'libx264'))->setKiloBitrate(1600);
        $highBitRateFromat = (new X264('aac', 'libx264'))->setKiloBitrate(3000);

        $convertedName  = '240-' . $video->video_path;
        $convertedName_360  = '360-' . $video->video_path;
        $convertedName_480  = '480-' . $video->video_path;
        $convertedName_720  = '720-' . $video->video_path;


        FFMpeg::fromDisk($video->disk)
            ->open($video->video_path)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(426, 240));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($lowBitRateFromat)
            ->save($convertedName)


            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(640, 360));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($low2_BitRatFromat)
            ->save($convertedName_360)


            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(854, 480));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($mediumBitRateFromat)
            ->save($convertedName_480)


            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(1200, 720));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($highBitRateFromat)
            ->save($convertedName_720);

        return redirect()->back()->with(
            'success',
            'سيكون المقطع متوفرا بعد الإنتهاء من معالجته'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
