<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index ()
    {
        $channels = User::all()->sortByDesc('create_at');

        $title = 'أحدث القنوات';
        return view('channels.index', compact('channels', 'title'));
    }

    public function indexBlocked ()
    {
        $users = User::where('block', 1)->get();
        return view('admin.channels.blocked', compact('users'));
    }

    public function adminIndex()
    {
        $users = User::all()->sortByDesc('create_at');
        return view('admin.channels.index', compact('users'));
    }

    public function adminPermissions ()
    {
        $users = User::all()->sortByDesc('create_at');
        return view('admin.channels.permissions', compact('users'));
    }

    public function adminUnblock (User $user) 
    {
        $user->block = 0;
        $user->update();

        session()->flash('flash_message', 'تم تعديل حالية حظر المستخدم');
        return redirect(route('admin.channels.blocked'));
    }

    public function adminUpdate (Request $request, User $user)
    {
        $user->administration_level = $request->administration_level;
        $user->update();

        session()->flash('flash_message', 'تم تعديل صلاحية المستخدم');
        return redirect(route('admin.channels.permissions'));
    }

    public function adminBlock (Request $request, User $user)
    {
        $user->block = 1;
        $user->update();

        session()->flash('flash_message', 'تم تعديل حالية حظر المستخدم');
        return redirect(route('admin.channels.permissions'));
    }

    public function adminDestroy (User $user)
    {
        $user->delete();
        
        session()->flash('flash_message', 'تم  حذف المستخدم');
        return redirect(route('admin.channels.index'));
    }

    public function show (User $channel) 
    {
        $videos = $channel->videos;

        $title = 'فيديوهات قناة : ' . $channel->name ;
        return view('videos.my-videos', compact('videos', 'title')); 
    }

    public function search (Request $request)
    {
        $channels = User::where('name', 'LIKE', "%{$request->term}%")->paginate(12);
        $title = "عرض نتائج البحث عن :  {$request->term}";

        return view('channels.index', compact('channels', 'title'));
    }
}
