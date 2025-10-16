<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImageHelper
{
    private static function filePath($subFolder = '')
    {
        //   $subFolder = "/client/" . Auth::user()->id . $subFolder;
        // TODO check sub folder
        if ($subFolder != '') {
            $subFolder .= DIRECTORY_SEPARATOR;
        }

        ini_set('memory_limit', '128M');

        $information_file_path = $subFolder.'/image/';

        return $information_file_path;
    }

    public static function uploadThumbnail($image, $path)
    {
        if (! ImageHelper::is_base64($image)) {
            return $image;
        }

        $path = ImageHelper::filePath('/'.$path);
        ini_set('memory_limit', '128M');
        // create the folder if it does not exist

        if (! file_exists(public_path().$path)) {
            //TODO change mode code "Security"
            mkdir(public_path().$path, 0755, true);
        }

        try {
            $image = \Intervention\Image\Facades\Image::make($image)->resize(200, 150);
            //->resize(300, 300);
        } catch (\Exception $exception) {
            return null;
        }

        $imageName = md5(time().rand(0, 800000)).'.'.Str::after($image->mime(), '/');
        $fullPath = public_path().$path.$imageName;

        $image->save($fullPath);
        $image->destroy();

        return url('').ImageHelper::removeSlashes(''.$path.$imageName);
    }

    /**
     * @return null|string
     */
    public static function uploadImage($image, $path)
    {
        if (! ImageHelper::is_base64($image)) {
            return $image;
        }

        $path = ImageHelper::filePath('/'.$path);
        ini_set('memory_limit', '128M');
        // create the folder if it does not exist

        if (! file_exists(public_path().'/'.$path)) {
            //TODO change mode code "Security"
            mkdir(public_path().$path, 0755, true);
        }

        try {
            $image = \Intervention\Image\Facades\Image::make($image);
            //->resize(300, 300);
        } catch (\Exception $exception) {
            return null;
        }

        $imageName = md5(time().rand(0, 800000)).'.'.Str::after($image->mime(), '/');
        $fullPath = public_path().$path.$imageName;

        $image->save($fullPath);
        $image->destroy();

        return url('').ImageHelper::removeSlashes(''.$path.$imageName);
    }

    private static function is_base64($s)
    {
        if (strpos($s, 'data:image/') === 0) {
            return true;
        }

        return false;
    }

    public static function removeSlashes($path)
    {
        while (strpos($path, '\/') !== false) {
            $path = str_replace('\/', '/', $path);
        }

        while (strpos($path, '//') !== false) {
            $path = str_replace('//', '/', $path);
        }

        return $path;
    }

    public static function GeenerateImageUrlFromName($name)
    {
        return "https://ui-avatars.com/api/?name=$name,size=256";
    }
}
