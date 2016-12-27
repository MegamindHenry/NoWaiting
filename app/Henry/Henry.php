<?php

namespace App\Henry;

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

    	if(count($smsCode) = 0)
    	{
    		return ['error' => 'sms_code_does_not_match'];
    	}
    	else
    	{
    		;
    	}

        return ['error' => 'unknown'];
    }

}