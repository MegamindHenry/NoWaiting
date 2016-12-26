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
        // grab credentials from the request
        $credentials = $request->only('phone', 'code');

        $newRecord = new UserSmsLog();
        $newRecord->phone = rand(10000000000, 19999999999);
        $newRecord->code = rand(100000, 999999);
        if(! $newRecord->save())
        {
        	return response()->json(['error' => 'could_not_save'], 800);
        }

        $json_response = ['code' => 998];

        // all good so return the token
        return response()->json($json_response);
    }
}
