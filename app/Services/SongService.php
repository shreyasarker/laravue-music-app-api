<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SongService
{
    public function uploadSong($song)
    {
        $url = Cloudinary::uploadFile($song->getRealPath(), [
            'folder' => 'LaraVueMusicApp'
        ])->getSecurePath();

        return $url;
    }

    public function removeSong($songPath)
    {
        $path = explode('/', $songPath);
        $fileIdWithExtension = explode('.', $path[sizeof($path)-1]);
        $fileId = $fileIdWithExtension[0];

        return Cloudinary::destroy('LaraVueMusicApp/'.$fileId, ["resource_type" => "video"]);
    }
}
