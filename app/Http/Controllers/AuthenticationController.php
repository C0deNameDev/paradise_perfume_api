<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Mail\ForgotPassMail;
use App\Mail\SignUpMail;
use App\Models\Card;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    public function test()
    {
        return ['okay, good to start from test'];
    }

    public function getAuth()
    {
        $user = Auth::user();

        if ($user) {
            return $this->sendResponse('Authenticated user retrieved successfully', new UserResource($user));
        }
    }

    public function authenticate(AuthRequest $request)
    {
        $reqEmail = $request->email;
        $user = User::firstWhere('email', $reqEmail);
        try {
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    // if (! $user->email_verified_at) {
                    //     $one_time_token = strval(rand(1000000, 99999999));
                    //     $user->one_time_token = Hash::make($one_time_token);
                    //     // send the code via email
                    //     Mail::to($user)->send(new SignUpMail($one_time_token));

                    //     // if no errors save the user to db
                    //     $user->save();

                    //     return $this->sendError('email not verified', new UserResource($user), 403);
                    // }
                    $token = $user->createToken('auth_token')->plainTextToken;
                    $result = [
                        'user' => new UserResource($user),
                        'token' => $token,
                    ];

                    return $this->sendResponse('user authenticated', $result);
                }

                return $this->sendError('wrong password', '', 401);

            }

            return $this->sendError('wrong email', '', 401);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 'something went wrong', 500);
        }
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
        // check if email already registered
        $user = User::firstWhere('email', $request->email);
        if ($user) {
            return $this->sendError('email already used', '', 409);
        }

        // create the new user's object
        $user = new User();

        // populate columns
        $user->email_verified_at = null;
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone_number = $request->input('phone_number');
        $user->password = Hash::make($request->password);
        $user->profile_picture = $user->default_profile;
        $user->save();

        // generate the singUp confirmation code

        try {

            // $mail = $this->sendEmail($user);
            // if (! $mail['success']) {
            //     throw new Exception($mail['message']);
            // }

            $userController = new UserController();
            $image_uri = $userController->store_profile_picture($user, $request->profile_picture);
            $message = 'user created';
            if (! $image_uri) {
                $message = $message.', but could not store the image, using the default picture instead, you still can change this later';
            }
            $user->profile_picture = $image_uri;
            $user->save();

            return $this->sendResponse($message, new UserResource($user));
        } catch (Exception $e) {

            return $this->sendError($e->getMessage(), 'something went wrong', 500);
        }
    }

    public function validateSignUp($userId, $code)
    {
        $user = User::find($userId);

        if (! $user) {
            return $this->sendError('user not found', '', 401);
        }

        if ($user->email_verified_at) {
            return $this->sendError('email already verified', '', 200);
        }

        if (! $this->validateOneTimeToken($user->id, $code)) {
            return $this->sendError('invalid code', '', 401);
        }
        // mark the account as activated
        $user->email_verified_at = Carbon::now();
        $user->one_time_token = null;

        // Create the client instance for this user
        $client = Client::create();
        $user->person()->associate($client);
        $user->person_type = $user->types['client'];

        // assign a card to this client
        Card::create(['client_id' => $client->id]);

        // save the new user to the database
        $user->save();

        return $this->sendResponse('validated');
    }

    public function forgotPassword($email)
    {

        $user = User::firstWhere('email', $email);
        if (! $user) {
            return $this->sendError('user not found', '', 404);
        }

        $code = strval(rand(1000000, 99999999));
        try {
            Mail::to($user)->send(new ForgotPassMail($code));
            $user->one_time_token = Hash::make($code);
            $user->save();

            return $this->sendResponse('password reset code sent via email', new UserResource($user), 200);
        } catch (Exception $e) {
            return $this->sendError('something went wrong', $e->getMessage(), 500);
        }
    }

    public function validateForgotPassword($userId, $code)
    {
        $result = $this->validateOneTimeToken($userId, $code);
        if ($result['success']) {
            $user = User::find($userId);
            $user->one_time_token = null;
            $user->save();

            return $this->sendResponse($result['message'], '', 200);
        }

        return $this->sendError($result['message'], '', 401);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::find($request->input('userId'));
        if (! $user) {
            return $this->sendError('user not found', '', 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $this->sendResponse('password updated', '', 200);
    }

    public function sendCofirmSignUp($userId)
    {
        $user = User::find($userId);
        if (! $user) {
            return $this->sendError('user not found', '', 404);
        }

        return ($this->sendEmail($user))['success'] ? $this->sendResponse('email sent', new UserResource($user)) : $this->sendError('could not send the error', '', 500);
    }

    // These Functions are not accesses directly from the api routes, they are used by other functions internally
    public function validateOneTimeToken($userId, $token)
    {
        $user = User::find($userId);
        if ($user) {
            if ($user->one_time_token) {
                if (Hash::check($token, $user->one_time_token)) {
                    $response = [
                        'success' => true,
                        'message' => 'code validated',
                        'data' => '',
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'code not validated',
                        'data' => '',
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'no token found',
                    'data' => '',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'user not found',
                'data' => '',
            ];
        }

        return $response;
    }

    public function sendEmail(User $user)
    {
        try {
            $one_time_token = strval(rand(1000000, 99999999));
            $user->one_time_token = Hash::make($one_time_token);
            // send the code via email
            Mail::to($user)->send(new SignUpMail($one_time_token));

            // if no errors save the user to db
            $user->save();
            $response = [
                'success' => true,
                'message' => 'mail sent',
                'data' => '',
            ];
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'errors while sending the mail',
                'data' => '',
            ];
        }

        return $response;
    }
}
