<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function convertedVideos ()
    {
        return $this->hasMany(Convertedvideo::class);
    }

    public function likes ()
    {
        return $this->hasMany(Like::class);
    }

    public function views ()
    {
        return $this->hasMany(View::class);
    }

    public function comments ()
    {
        return $this->hasMany(Comment::class);
    }

    public function users ()
    {
        return $this->belongsToMany(User::class, 'video_user')->withPivot('id')->withTimestamps();
    }
}
