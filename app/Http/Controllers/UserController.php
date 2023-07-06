<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
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
}
