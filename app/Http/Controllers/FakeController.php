<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserSmsLog;

class FakeController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Add SMS Record
     *
     * @return \Illuminate\Http\Response
     */
    public function addSMS(Request $request)
    {
        $newRecord = new UserSmsLog();
        $newRecord->phone = rand(10000000000, 19999999999);
        $newRecord->code = rand(100000, 999999);
        $newRecord->action = 'fake';
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
