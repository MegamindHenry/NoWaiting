<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Helpers\ResponseHelper;
use App\UserSmsLog;
use Validator;
use App\Http\Controllers\Controller;

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
}
