<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Like::factory()->count(25)->create();
    }
}
