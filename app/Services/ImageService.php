<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageService
{
    public function uploadImage($base64Image)
    {
        //The base64 encoded image data
        $image64 = $base64Image;

        $url = Cloudinary::upload($image64, [
            'folder' => 'LaraVueMusicApp'
        ])->getSecurePath();

        return $url;
    }

    public function removeImage($imagePath)
    {
        $path = explode('/', $imagePath);
        $fileIdWithExtension = explode('.', $path[sizeof($path)-1]);
        $fileId = $fileIdWithExtension[0];

        return Cloudinary::destroy('LaraVueMusicApp/'.$fileId);
    }
}
