<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($message, $data = null, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode);
    }

    public function sendError($message, $errorsRaw = null, $statusCode)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errorsRaw
        ];

        return response()->json($response, $statusCode);
    }
}