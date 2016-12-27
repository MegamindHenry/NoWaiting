<?php

namespace App\Http\Helpers;

class ResponseHelper {

    public static function formatResponse($code, $message, $data)
    {
        $response = [
        	'code' => $code,
        	'message' => $message,
        	'data' => $data
        ];

        return $response;
    }

}