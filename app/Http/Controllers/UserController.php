<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function store_profile_picture(User $user, $image)
    {
        try {
            $pattern = '/^(\w+)\|(.+)$/';

            if (! preg_match($pattern, $image, $matches)) {
                throw new Exception('Invalid image format');
            }
            $image_ext = explode('|', $image)[0];
            $image_b64 = explode('|', $image)[1];
            $image_b64 = str_replace(' ', '+', $image_b64);
            $imageName = $user->id.'.'.$image_ext;
            $image_uri = storage_path().'/app/public/profile_pictures/'.$imageName;
            File::put($image_uri, base64_decode($image_b64));

            return $image_uri;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPicture($userId)
    {
        try {
            $image_array = File::glob(storage_path().'/app/public/profile_pictures/'.$userId.'.*');
            if (! empty($image_array)) {
                $binary_image = File::get($image_array[0]);
                $b64_image = base64_encode($binary_image);
                // dd($b64_image);

                return $this->sendResponse('picture found', $b64_image);
            } else {
                return $this->sendError('picture not found', '', 400);
            }
        } catch (FileNotFoundException $fileNotFound) {
            return $this->sendError('file not found', '', 404);
        }
    }
}
