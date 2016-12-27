<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\UserSmsLog;
use App\AppUser;
use Validator;
use Henry;

class AppUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['store', 'registerSMS']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = JWTAuth::parseToken()->authenticate();

        $response = ResponseHelper::formatResponse('998', 'success', ['user' => $user]);
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $validateRegister = Henry::validateRegister($phone, $code);

        if(isset($validateRegister['error']))
        {
            $response = ResponseHelper::formatResponse('801', 'error', $validateRegister['error']);
            return response()->json($response);
        }

        $userId = Henry::registerUser($phone, $code);

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

        $response = ResponseHelper::formatResponse('998', 'success', array());

        // all good so return the token
        return response()->json($response);
    }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
