<?php

namespace App\Http\Helpers;
use App\Http\Helpers\ResponseHelper;

class DebugHelper {

    public static function dump($var)
    {
        $response = ResponseHelper::formatResponse('999', 'debug', $var);
        return response()->json($response);
    }

}