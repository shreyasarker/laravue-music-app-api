<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SongService
{
    public function uploadSong($song, $songPath)
    {
        $songName = $song->getClientOriginalName();
        Storage::putFileAs($songPath, $song, $songName);
        $url = Storage::url($songPath . '/' . $songName);
        return $url;
    }

    public function removeSong($songPath)
    {
        return File::delete(public_path($songPath));

    }
}
