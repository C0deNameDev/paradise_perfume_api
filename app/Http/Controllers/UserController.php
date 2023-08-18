<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct(private User $user, private Client $client)
    {
    }

    public function search_client($query)
    {

        try {
            $user = $this->user::where('email', $query)->get()->first();
            if ($user) {
                $client = $user->person;
                $result = [
                    ['first_name' => $client->first_name,
                        'last_name' => $client->last_name,
                        'profile_picture' => $user->profile_picture,
                        'phone_number' => $client->phone_number, ],
                ];

                return $this->sendResponse('user found', $result);
            }

            $client = $this->client::where('phone_number', $query)->get()->first();
            if ($client) {
                $result = [
                    ['first_name' => $client->first_name,
                        'last_name' => $client->last_name,
                        'profile_picture' => $client->user->profile_picture,
                        'phone_number' => $client->phone_number, ],
                ];

                return $this->sendResponse('users found', $result);
            }

            $first_name = explode('_', $query)[0];
            $last_name = explode('_', $query)[1];
            $client = null;
            $client = $this->client::where('first_name', $first_name)->get();
            $result = [];

            foreach ($client as $c) {
                array_push($result, [
                    'first_name' => $c->first_name,
                    'last_name' => $c->last_name,
                    'profile_picture' => $c->user->profile_picture,
                    'phone_number' => $c->phone_number,
                ]);
            }

            $client = $this->client::where('first_name', $last_name)->get();

            foreach ($client as $c) {
                array_push($result, [
                    'first_name' => $c->first_name,
                    'last_name' => $c->last_name,
                    'profile_picture' => $c->user->profile_picture,
                    'phone_number' => $c->phone_number,
                ]);
            }

            if (! empty($result)) {
                return $this->sendResponse('users found', $result);

            }

            $this->sendError('not found', '', 404);
        } catch (\Throwable $e) {
            $this->sendError('internal server error', '', 500);
        }
    }

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
            $user = User::find($user_id);
            $imageKit = new ImageKitProvider();
            if (! $user) {
                return $this->sendError('user not found', '', 404);
            }
            if (! $user->profile_picture || $user->profile_picture === 'default') {
                return $this->sendResponse('picture found', $this->user->default_profile_picture);
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
