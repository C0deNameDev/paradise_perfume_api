<?php

namespace App\Http\Controllers;

// require_once __DIR__.'/vendor/aut/oload.php';
use Exception;
use GuzzleHttp\Client;
use ImageKit\ImageKit;

class ImageKitProvider
{
    protected $imageKit;

    public function __construct()
    {
        $this->imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_HOST').env('IMAGEKIT_ID'),
        );
    }

    public function store_profile_b64($image_b64, $image_name)
    {
        try {
            $imageUpload = $this->imageKit->uploadFile([
                'file' => $image_b64,
                'fileName' => $image_name,
                'folder' => '/profile_pictures',

            ]);
            if ($imageUpload->error) {
                return false;
            }

            return $imageUpload;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_profile_picture($image_name)
    {

        try {
            $imageKitUrl = env('IMAGEKIT_HOST').env('IMAGEKIT_ID').'/profile_pictures/'.$image_name;

            $client = new Client();
            $response = $client->get($imageKitUrl);
            if ($response->getStatusCode() === 200) {
                // dd(base64_encode($response->getBody()->getContents()));

                return base64_encode($response->getBody()->getContents());
            }

            return false;
        } catch (Exception $e) {
        }
    }
}
