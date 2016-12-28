<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userInfo()
    {
        return $this->hasOne('App\UserInfo');
    }

    public function userInfo()
    {
        return $this->hasOne('App\UserInfo');
    }
}
