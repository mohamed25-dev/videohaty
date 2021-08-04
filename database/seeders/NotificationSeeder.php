<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'notification' => ('مراجعة جهاز  Kindle'),
                'success' => '1',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'notification' => ('دورة قواعد البيانات'),
                'success' => '1',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'notification' => ('يوم في حياة مهندس برمجيات'),
                'success' => '0',
            ]);
        }
    }
}
