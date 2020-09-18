<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth-admin']], function () {
    Route::get('/admin/product', 'HttpUploadExcelFileOfProductAndBulkInsert@getPage')->name('admin-upload-products');

    Route::Post('/admin/product', 'HttpUploadExcelFileOfProductAndBulkInsert@__invoke')->name('admin-upload-file');
});
