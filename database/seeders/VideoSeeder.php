<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $video1 = Video::create([
            'user_id' => User::where('name', 'admin')->first()->id,
            'title' => 'دورة علوم الحاسوب - أكاديميتي ',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'image1.png',
            'hours' => '1',
            'minutes'=>'30',
            'seconds'=>'4',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video2 = Video::create([
            'user_id' => User::where('name', 'admin')->first()->id,
            'title' => 'دورة git & github - أكاديميتي ',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'image2.png',
            'hours' => '1',
            'minutes'=>'5',
            'seconds'=>'14',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video3 = Video::create([
            'user_id' => User::where('name', 'mohamed')->first()->id,
            'title' => 'كتاب العادات الذرية',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'image3.png',
            'hours' => '0',
            'minutes'=>'10',
            'seconds'=>'50',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video4 = Video::create([
            'user_id' => User::where('name', 'mohamed')->first()->id,
            'title' => 'فضفضة عن الزواج وأشياء أخرى',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'image4.png',
            'hours' => '0',
            'minutes'=>'7',
            'seconds'=>'30',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video5 = Video::create([
            'user_id' => User::where('name', 'mohamed2')->first()->id,
            'title' => 'دورة  قواعد البيانات - أكاديميتي ',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'image5.png',
            'hours' => '0',
            'minutes'=>'45',
            'seconds'=>'25',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video6 = Video::create([
            'user_id' => User::where('name', 'mohamed1')->first()->id,
            'title' => 'يومي في حياة مهنس برمجيات @ غوغل',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'test/computer-science/computer-science.png',
            'hours' => '0',
            'minutes'=>'8',
            'seconds'=>'4',
            'quality'=>'720',
            'processed'=>'1',
        ]);

        $video7 = Video::create([
            'user_id' => User::where('name', 'mohamed1')->first()->id,
            'title' => 'رحلتي إلى المالديف',
            'disk' => 'test',
            'video_path' => 'test',
            'image_path' => 'test/computer-science/computer-science.png',
            'hours' => '0',
            'minutes'=>'20',
            'seconds'=>'44',
            'quality'=>'720',
            'processed'=>'1',
        ]);
    }
}
