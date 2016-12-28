<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    //
    public function appUser()
    {
        return $this->belongsTo('App\UserInfo');
    }
}
