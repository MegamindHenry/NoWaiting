<?php

namespace App\Http\Helpers;

class TimeHelper {

    public static function validateTimeExpire($first, $second, $minutes)
    {
		$third = $second->addMinutes($minutes);
		if($first->gt($third))
		{
			return false;
		}
		else
		{
			return true;
		}
    }
}