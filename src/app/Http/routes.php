<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::group(['namespace' => 'SaeedVaziry\LaravelFilemanager\App\Http\Controllers', 'middleware' => 'web'], function () {
    Route::get(config('filemanager.basicRoute'), 'FilemanagerController@getIndex');
    Route::post(config('filemanager.basicRoute').'/upload', 'FilemanagerController@postUpload');
    Route::get(config('filemanager.basicRoute').'/delete', 'FilemanagerController@getDelete');
});
