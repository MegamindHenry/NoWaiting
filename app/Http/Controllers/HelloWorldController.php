<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Hello World
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Hello World';
    }
}
