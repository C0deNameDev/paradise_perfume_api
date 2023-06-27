<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function test()
    {
        return ["okay, good to start from test"];
    }
    public function authenticate(AuthRequest $request)
    {
        $reqEmail = $request->email;
        $user = User::firstWhere('email', $reqEmail);
        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('auth_token')->plainTextToken;
                $result = [
                    "user" => new UserResource($user),
                    "token" => $accessToken
                ];
                return $this->sendResponse("user authenticated", $result);
            }

            return $this->sendError("wrong password", "", 400);


        }
        return $this->sendError("wrong email", "", 400);
    }
}