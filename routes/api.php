<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::Post('/login', 'AuthController@login')->name('Api_login');
Route::Get('/product', 'API\HttpApiGetPaginatedListOfProducts');

Route::group(['middleware' => ['auth']], function () {
    Route::Get('/logout', 'AuthController@logout')->name('Api_logout');
    Route::Get('/me', 'AuthController@me')->name('Api_user_info');
});

