<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseHelper;
use App\UserSmsLog;
use Validator;
use App\Http\Controllers\Controller;
use Henry;

class SendSMSController extends Controller
{
    //
    public function registerSMS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|size:11',
        ]);

        if ($validator->fails()) {
            $response = ResponseHelper::formatResponse('801', 'error', $validator->errors());
            return response()->json($response);
        }

        $requestData = $request->only('phone');
        $phone = $requestData['phone'];

        $newRecord = new UserSmsLog();
        $newRecord->phone = $phone;
        $newRecord->code = rand(100000, 999999);
        $newRecord->action = 'register';
        if(! $newRecord->save())
        {
            $response = ResponseHelper::formatResponse('800', 'could_not_save', array());
            return response()->json($response);
        }

        $response = ResponseHelper::formatResponse('998', 'success', array());

        // all good so return the token
        return response()->json($response);
    }

    public function loginSMS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|size:11',
        ]);

        if ($validator->fails()) {
            $response = ResponseHelper::formatResponse('801', 'error', $validator->errors());
            return response()->json($response);
        }

        $requestData = $request->only('phone');
        $phone = $requestData['phone'];

        if(!Henry::validateUserExist($phone))
        {
        	$response = ResponseHelper::formatResponse('800', 'have_not_register_yet', array());
            return response()->json($response);
        }

        $newRecord = new UserSmsLog();
        $newRecord->phone = $phone;
        $newRecord->code = rand(100000, 999999);
        $newRecord->action = 'login';
        if(! $newRecord->save())
        {
            $response = ResponseHelper::formatResponse('800', 'could_not_save', array());
            return response()->json($response);
        }

        $response = ResponseHelper::formatResponse('998', 'success', array());

        // all good so return the token
        return response()->json($response);
    }
}
