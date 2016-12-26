<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

$api->version('v1', function ($api) {
    $api->get('helloworld', 'App\Http\Controllers\API\HelloWorldController@index');
    $api->post('authenticate', 'App\Http\Controllers\API\HelloWorldController@authenticate');
});

$api->version('v1', ['middleware' => 'jwt.auth'],function ($api) {
    $api->get('info', 'App\Http\Controllers\API\HelloWorldController@info');
});
