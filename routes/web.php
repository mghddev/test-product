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

Route::middleware('auth-admin')
    ->get('/admin/product', function () {
        return view('upload-products');
    })->name('admin-upload-products');


Route::group(['middleware' => ['auth-admin']], function () {
    Route::Post('/admin/product', 'HttpUploadExcelFileOfProductAndBulkInsert')->name('admin-upload-file');
});
