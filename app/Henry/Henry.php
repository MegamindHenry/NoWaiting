<?php

namespace App\Henry;

use Carbon\Carbon;
use DB;
use App\User;
use App\Http\Helpers\TimeHelper;
use JWTAuth;
use App\UserSMSLog;

class Henry {
	public function validateUserExist($phone)
	{
		$appUsers = DB::table('app_users')
			->where('phone', '=', $phone)
			->get();

		if(count($appUsers) > 0)
		{
			return true;
		}

		return false;
	}

    public function validateRegister($phone, $code, $action)
    {
    	$users = DB::table('app_users')
    		->where('phone', '=', $phone)
    		->get();

    	if(count($users) > 0)
    	{
    		return ['error' => 'already_registered'];
    	}

    	$smsCode = DB::table('user_sms_logs')
    		->where('phone', '=', $phone)
    		->where('code', '=', $code)
    		->where('action', '=', $action)
    		->latest()
    		->first();

    	if(!$smsCode)
    	{
    		return ['error' => 'sms_code_does_not_match'];
    	}
    	else
    	{
    		$createdAt = $smsCode->created_at;
    		$carbonNow = Carbon::now('GMT+8');
    		$carbonCreated = new Carbon($createdAt);

    		if(!TimeHelper::validateTimeExpire($carbonNow, $carbonCreated, 10))
    		{
    			return ['error' => 'sms_code_expired'];
    		}
    		else
    		{
    			return true;
    		}
    	}

        return ['error' => 'unknown'];
    }

    public function validateLogin($phone, $code, $action)
    {
    	$users = DB::table('app_users')
    		->where('phone', '=', $phone)
    		->get();

    	if(count($users) == 0)
    	{
    		return ['error' => 'have_not_register_yet'];
    	}

    	$smsCode = DB::table('user_sms_logs')
    		->where('phone', '=', $phone)
    		->where('code', '=', $code)
    		->where('action', '=', $action)
    		->latest()
    		->first();

    	if(!$smsCode)
    	{
    		return ['error' => 'sms_code_does_not_match'];
    	}
    	else
    	{
    		$createdAt = $smsCode->created_at;
    		$carbonNow = Carbon::now('GMT+8');
    		$carbonCreated = new Carbon($createdAt);

    		if(!TimeHelper::validateTimeExpire($carbonNow, $carbonCreated, 10))
    		{
    			return ['error' => 'sms_code_expired'];
    		}
    		else
    		{
    			return $users[0];
    		}
    	}

        return ['error' => 'unknown'];
    }

    public function registerUser($phone, $code)
    {
    	$newRecord = new User();
    	$newRecord->name = md5($phone);
    	$newRecord->email = md5($phone);
    	$newRecord->password = md5($code);
    	if(!$newRecord->save())
    	{
    		return ['error' => 'could_not_create_user'];
    	}

    	return $newRecord->id;
    }

    public function getUserByPhone($phone)
    {
    	;
    }

    public function getTokenByUser($user)
    {
        $token = JWTAuth::fromUser($user);
        return $token;
    }

    public function getUserByToken($token)
    {
        $user = JWTAuth::setToken($token)->authenticate();

        return $user;
    }

    public function sendSMS($phone, $method){
        $newRecord = new UserSmsLog();
        $newRecord->phone = $phone;
        $newRecord->code = rand(100000, 999999);
        $newRecord->action = $method;
        if(! $newRecord->save())
        {
            return false;
        }
        return true;
    }
}