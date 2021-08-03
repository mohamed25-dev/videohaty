<?php

namespace App\Http\Controllers;

use Aws\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index () 
    {
        $videos = auth()->user()->videoInHistory;
        $title = 'سجل المشاهدة';

        return view('history.index', compact('videos', 'title'));
    }

    public function destroy ($id) 
    {
        auth()->user()->videoInHistory()->wherePivot('id', $id)->detach();
        return back()->with('success', 'حُذف الفيديو');
    }

    public function destroyAll () 
    {
        auth()->user()->videoInHistory()->detach();
        return back()->with('success', 'حُذف سجل المشاهدات');
    }
}
