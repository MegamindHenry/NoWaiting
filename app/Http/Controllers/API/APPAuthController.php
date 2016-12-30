<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseHelper;
use App\Http\Helpers\DebugHelper;
use Validator;
use App\Http\Controllers\Controller;
use Henry;

class APPAuthController extends Controller
{
    public function aggregate(Request $request)
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

        if(Henry::validateUserExist($phone))
        {
            $validateLogin = Henry::validateLogin($phone, $code, 'aggregate');

            // $response = ResponseHelper::formatResponse('998', 'success', array('token' => $validateLogin));
            // return response()->json($response);

            return DebugHelper::dump($validateLogin);
            die;

            if(isset($validateLogin['error']))
            {
                $response = ResponseHelper::formatResponse('801', 'error', $validateLogin['error']);
                return response()->json($response);
            }

            $token = Henry::getTokenByUser($validateLogin);

            $response = ResponseHelper::formatResponse('998', 'success', array('token' => $token));
            return response()->json($response);
        }
        else
        {
            $validateRegister = Henry::validateRegister($phone, $code);

            if(isset($validateRegister['error']))
            {
                $response = ResponseHelper::formatResponse('801', 'error', $validateRegister['error']);
                return response()->json($response);
            }

            $userId = Henry::registerUser($phone, $code, 'aggregate');

            if(isset($userId['error']))
            {
                $response = ResponseHelper::formatResponse('801', 'error', $userId['error']);
                return response()->json($response);
            }


            $newRecord = new AppUser();
            $newRecord->phone = $phone;
            $newRecord->user_id = $userId;

            if(! $newRecord->save())
            {
                $response = ResponseHelper::formatResponse('800', 'could_not_save', array());
                return response()->json($response);
            }

            $token = Henry::getTokenByUser($newRecord);

            $response = ResponseHelper::formatResponse('998', 'success', array('token' => $token));

            // all good so return the token
            return response()->json($response);
        }
    }
}
