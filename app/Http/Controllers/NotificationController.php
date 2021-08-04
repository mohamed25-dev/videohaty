<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index ()
    {
        $notifications = auth()->user()->notifications->sortByDesc('created_at')->take(4);
        $items = array_values($notifications->toArray());

        $alert = Alert::where('user_id', auth()->id())->first();
        $alert->alert = 0;
        $alert->save();

        return response()->json(['someNotifications' => $items]);
    }

    public function allNotifications ()
    {
        $notifications = auth()->user()->notifications->sortByDesc('created_at');
        $title = 'جميع الإشعارات';

        return view('notifications.index', compact('notifications', 'title'));

    }
}
