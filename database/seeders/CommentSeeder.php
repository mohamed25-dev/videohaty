<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Convertedvideo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
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
            Comment::create([
                'user_id' => 1,
                'video_id' => $video->id,
                'body' => 'مقطع رائع جزيت خيرا',
            ]);

            Comment::create([
                'user_id' => 2,
                'video_id' => $video->id,
                'body' => 'أعجبتني الفكرة وطريقة عرضها .. شكرا لك',
            ]);

            Comment::create([
                'user_id' => 3,
                'video_id' => $video->id,
                'body' => 'جميل جدا',
            ]);
        }
    }
}
