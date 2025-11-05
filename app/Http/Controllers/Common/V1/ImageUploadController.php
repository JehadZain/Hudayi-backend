<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $image = $request->file('image');
            $imageName = time().'.'.$image->extension();

            $image->storeAs('public/images', $imageName);

            $compressedImage = Image::make(storage_path('app/public/images/'.$imageName))->resize(300, 200);
            $compressedImage->save(storage_path('app/public/images/compressed_'.$imageName));


            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'image_name' => $imageName,
                    'compressed_image_name' => 'compressed_'.$imageName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
