<?php

namespace Database\Seeders;

use App\Models\Convertedvideo;
use App\Models\Video;
use Illuminate\Database\Seeder;

class ConvertedVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $videos = Video::all();
        foreach ($videos as $video) {
            Convertedvideo::create([
                'video_id' => $video->id,
                'mp4_Format_240' => 'test/computer-science/240p-computer-science.mp4',
                'mp4_Format_360' => 'test/computer-science/360p-computer-science.mp4',
                'mp4_Format_480' => 'test/computer-science/480p-computer-science.mp4',
                'mp4_Format_720' => 'test/computer-science/720p-computer-science.mp4',
                'mp4_Format_1080' => 'No Video',
                'webm_Format_240'=>'test/computer-science/240p-computer-science.webm',
                'webm_Format_360'=>'test/computer-science/360p-computer-science.webm',
                'webm_Format_480'=>'test/computer-science/480p-computer-science.webm',
                'webm_Format_720'=>'test/computer-science/720p-computer-science.webm',
                'webm_Format_1080'=>'No Video',
            ]);
        }
    }
}
