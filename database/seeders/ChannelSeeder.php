<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channel1 = User::create([
            'name' => "admin",
            'email' => 'admin@email.com',
            'password' => bcrypt('11112222'),
            'administration_level' => '2',
            'current_team_id' => '1',
        ]);

        $channel2 = User::create([
            'name' => "mohamed",
            'email' => 'mohamed@email.com',
            'password' => bcrypt('11112222'),
            'administration_level' => '0',
            'current_team_id' => '1',
        ]);

        $channel1 = User::create([
            'name' => "mohamed1",
            'email' => 'mohamed1@email.com',
            'password' => bcrypt('11112222'),
            'administration_level' => '2',
            'current_team_id' => '1',
        ]);

        $channel1 = User::create([
            'name' => "mohamed2",
            'email' => 'mohamed2@email.com',
            'password' => bcrypt('11112222'),
            'administration_level' => '2',
            'current_team_id' => '1',
        ]);

    }
}
