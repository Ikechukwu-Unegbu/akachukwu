<?php
namespace App\Helpers;

class ApiHelper
{
    public static function sendResponse($result, $message)
    {
        $response = [
            'status'    => true,
            'response'  => $result,
            'message'   => $message,

        ];
        return response()->json($response, 200)->getData();
    }

    public static function sendError($errors, $feedback, $code = 401)
    {
        $response = [
            'status' => false,
            'errors' => $errors,
            'message' => $feedback
        ];

        return response()->json($response, $code)->getData();
    }
}