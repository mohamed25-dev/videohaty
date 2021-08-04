<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            ChannelSeeder::class,
            VideoSeeder::class,
            ConvertedVideoSeeder::class,
            AlertSeeder::class,
            NotificationSeeder::class,
            ViewSeeder::class,
            CommentSeeder::class,
            LikeSeeder::class
        ]);
    }
}
