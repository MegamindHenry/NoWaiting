<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseHelper;
use Validator;
use App\Http\Controllers\Controller;
use Henry;

class APPAuthController extends Controller
{
    public function SMSLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|size:11',
            'code' => 'required|size:6',
        ]);

        if ($validator->fails()) {
            $response = ResponseHelper::formatResponse('801', 'error', $validator->errors());
            return response()->json($response);
        }

        $requestData = $request->only('phone', 'code');
        $phone = $requestData['phone'];
        $code = $requestData['code'];

        $validateLogin = Henry::validateLogin($phone, $code);

        if(isset($validateRegister['error']))
        {
            $response = ResponseHelper::formatResponse('801', 'error', $validateRegister['error']);
            return response()->json($response);
        }

        $response = ResponseHelper::formatResponse('998', 'success', array());

        // all good so return the token
        return response()->json($response);
    }
}
