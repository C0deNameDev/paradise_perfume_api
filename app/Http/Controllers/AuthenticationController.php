<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

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

            // return (response()->json([$user]));
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('auth_token')->plainTextToken;
                $result = [
                    "user" => new UserResource($user),
                    "token" => $accessToken
                ];
                return $this->sendResponse("user authenticated", $result);
            }

            return $this->sendError("wrong password", "", 401);


        }
        return $this->sendError("wrong email", "", 401);
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            $this->sendResponse("user loged out");
        } catch (\Exception $e) {
            $this->sendError("something went wrong", "");
        }
    }

    public function signUp(SignUpRequest $request)
    {
        dd(request()->input());
    }
}