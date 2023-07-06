<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Mail\SignUpMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    public function test()
    {
        return ['okay, good to start from test'];
    }

    public function authenticate(AuthRequest $request)
    {
        $reqEmail = $request->email;
        $user = User::firstWhere('email', $reqEmail);
        if ($user) {

            // return (response()->json([$user]));
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('auth_token')->plainTextToken;
                $result = [
                    'user' => new UserResource($user),
                    'token' => $accessToken,
                ];

                return $this->sendResponse('user authenticated', $result);
            }

            return $this->sendError('wrong password', '', 401);

        }

        return $this->sendError('wrong email', '', 401);
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return $this->sendResponse('user loged out');
        } catch (Exception $e) {
            return $this->sendError('something went wrong', '', 500);
        }
    }

    public function signUp(SignUpRequest $request)
    {

        $user = User::firstWhere('email', $request->email);
        if ($user) {
            return $this->sendError('email already used', '', 409);
        }

        $user = new User();
        $user->email_verified_at = null;
        $user->email = $request->input('email');
        $user->first_name = $request->input('firstname');
        $user->last_name = $request->input('lastname');
        $user->phone_number = $request->input('phoneNumber');
        $user->password = Hash::make($request->password);
        $user->profile_picture = $user->default_profile;
        $reg_token = strval(rand(1000000, 99999999));
        $user->reg_token = Hash::make($reg_token);

        try {
            Mail::to($user)->send(new SignUpMail($reg_token));
            $user->save();
            $image_uri = $this->store_profile_picture($user, $request->image);
            $message = 'confirmation mail sent';
            if (! $image_uri) {
                $message = $message.', but could not store the image, using the default picture instead, you still can change this later';
            }
            $user->profile_picture = $image_uri;
            $user->save();

            return $this->sendResponse('confirmation mail sent');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 'something went wrong', 500);
        }
    }

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
