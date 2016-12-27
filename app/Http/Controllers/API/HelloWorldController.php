<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Henry;
use App\Http\Helpers\ResponseHelper;

class HelloWorldController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['authenticate', 'index']]);
    }

    /**
     * Hello World
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = ResponseHelper::formatResponse('998', 'success', array());
        return response()->json($response);
    }

    /**
     * JWT Auth
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {

                $response = ResponseHelper::formatResponse('401', 'invalid_credentials', array());
                return response()->json($response);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            $response = ResponseHelper::formatResponse('500', 'could_not_create_token', array());
            return response()->json($response);
        }

        // all good so return the token
        $response = ResponseHelper::formatResponse('998', 'success', ['token' => $token]);
        return response()->json($response);
    }

    /**
     * Get User Info
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $response = ResponseHelper::formatResponse('998', 'success', ['user' => $user]);
        return response()->json($response);
    }   
}
