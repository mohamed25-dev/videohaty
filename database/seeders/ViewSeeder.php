<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use App\Models\View;
use Illuminate\Database\Seeder;

class ViewSeeder extends Seeder
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
            View::create([
                'user_id' => 2,
                'video_id' => $video->id,
                'views_number' => 3,
            ]);

            View::create([
                'user_id' => 4,
                'video_id' =>  $video->id,
                'views_number' => 7,
            ]);

            View::create([
                'user_id' => 3,
                'video_id' =>  $video->id,
                'views_number' => 4,
            ]);
        }
    }
}
