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

            $image_b64 = explode('|', $image)[1];
            $image_b64 = str_replace(' ', '+', $image_b64);
            $image_name = 'profile_'.$user->id;
            // $image_uri = storage_path().'/app/public/profile_pictures/'.$imageName;
            // File::put($image_uri, base64_decode($image_b64));
            $imgKit = new ImageKitProvider();

            $imageUpload = $imgKit->store_profile_b64($image_b64, $image_name);
            print_r($imageUpload);
            if ($imageUpload) {
                return $imageUpload->result->name;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_profile_picture($user_id)
    {
        try {
            $user = USER::find($user_id);
            $imageKit = new ImageKitProvider();
            if (! $user) {
                return $this->sendError('user not found', '', 404);
            }
            $picture = $imageKit->get_profile_picture($user->profile_picture);
            if ($picture) {
                return $this->sendResponse('picture found', $picture);
            }

            return $this->sendError('could not fetch image', '', 500);

        } catch (Exception $e) {

            return $this->sendError('error', '', 500);
        }
    }
}
