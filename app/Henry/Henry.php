<?php

namespace App\Henry;

use Carbon\Carbon;
use DB;
use App\User;

class Henry {

    public function validateRegister($phone, $code)
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
    		->where('action', '=', 'register')
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
    		$carbonExpire = $carbonCreated->addMinutes(10);
    		if($carbonNow->gt($carbonExpire))
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

}