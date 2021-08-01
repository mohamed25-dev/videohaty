<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;


class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function convertVideo($bitRate, $width, $hieght)
    {

        $mp4BitRateFormat = (new X264('aac', 'libx264'))->setKiloBitrate($bitRate);
        $webmBitRateFormat = (new WebM('libvorbis', 'libvpx'))->setKiloBitrate($bitRate);

        $convertedName  = 'mp4' . '-' . $hieght . '-' . $this->video->video_path;

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)
            ->addFilter(function ($filters) use ($width, $hieght) {
                $filters->resize(new Dimension($width, $hieght));
            })
            ->export()
            ->toDisk(env('FILESYSTEM_DRIVER'))
            ->inFormat($mp4BitRateFormat)
            ->save($convertedName);

        $convertedName  = 'webm' . '-' . $hieght . '-' . $this->video->video_path;

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)
            ->addFilter(function ($filters) use ($width, $hieght) {
                $filters->resize(new Dimension($width, $hieght));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($webmBitRateFormat)
            ->save($convertedName);
    }

    public function handle()
    {
        $ffprobe = FFProbe::create();
        $videoData = $ffprobe
            ->streams('/path/to/video/mp4')
            ->videos(public_path('/storate/' . $this->video->video_path))
            ->first();
        
        $width = $videoData->get('width');
        $width = $videoData->get('height');

        $media = FFMpeg::fromDisk($this->video->disk)
                    ->open($this->video->video_path);

        $durationInSeconds = $media->getDurationInSeconds();
        $hours = floor($durationInSeconds/3600);
        $minutes = floor(($durationInSeconds / 60) %60);
        $seconds = $durationInSeconds % 60;


        $this->convertVideo(400, 426, 240);

        $this->convertVideo(800, 640, 360);

        $this->convertVideo(1600, 854, 480);

        $this->convertVideo(3000, 1200, 720);
    }
}



        // $convertedName  = '240-' . $this->video->video_path;
        // $convertedName_360  = '360-' . $this->video->video_path;
        // $convertedName_480  = '480-' . $this->video->video_path;
        // $convertedName_720  = '720-' . $this->video->video_path;


        // FFMpeg::fromDisk($this->video->disk)
        //     ->open($this->video->video_path)

        //     ->addFilter(function ($filters) {
        //         $filters->resize(new Dimension(426, 240));
        //     })
        //     ->export()
        //     ->toDisk('public')
        //     ->inFormat($lowBitRateFromat)
        //     ->save($convertedName)


        //     ->addFilter(function ($filters) {
        //         $filters->resize(new Dimension(640, 360));
        //     })
        //     ->export()
        //     ->toDisk('public')
        //     ->inFormat($low2_BitRatFromat)
        //     ->save($convertedName_360)


        //     ->addFilter(function ($filters) {
        //         $filters->resize(new Dimension(854, 480));
        //     })
        //     ->export()
        //     ->toDisk('public')
        //     ->inFormat($mediumBitRateFromat)
        //     ->save($convertedName_480)


        //     ->addFilter(function ($filters) {
        //         $filters->resize(new Dimension(1200, 720));
        //     })
        //     ->export()
        //     ->toDisk('public')
        //     ->inFormat($highBitRateFromat)
        //     ->save($convertedName_720);
