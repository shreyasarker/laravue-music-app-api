<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadImage($base64Image, $name, $imagePath)
    {
        //The base64 encoded image data
        $image64 = $base64Image;

        // explode the image to get the extension
        $extension = explode(';base64', $image64);

        //from the first element
        $extension = explode('/', $extension[0]);

        // from the 2nd element
        $extension = $extension[1];
        $replace = substr($image64, 0, strpos($image64, ',') + 1);

        // finding the substring from
        // replace here for example in our case: data:image/png;base64,
        $image = str_replace($replace, '', $image64);

        // replace
        $image = str_replace(' ', '+', $image);

        // set the image name using the time and a random string plus
        // an extension
        $imageName = time() . '_' . $name . '.' . $extension;

        // save the image in the image path we passed from the
        // function parameter.
        Storage::put($imagePath . '/' . $imageName, base64_decode($image), 'public');

        // return the image url and feed to the function that requests it
        $url = Storage::url($imagePath . '/' . $imageName);

        return $url;
    }

    public function removeImage($imagePath)
    {
        return File::delete(public_path($imagePath));

    }
}
