<?php

namespace App\Henry\Facades;

use Illuminate\Support\Facades\Facade;

class Henry extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'henry';
    }
}