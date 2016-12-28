<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    //
    public function appUser()
    {
        return $this->belongsTo('App\UserInfo');
    }
}
