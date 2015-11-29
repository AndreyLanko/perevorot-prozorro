<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::get('/v2', 'HomeController@index2');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('json/combined', 'JsonController@combined');
Route::get('json/parsed', 'JsonController@parsed');
Route::get('json/raw', 'JsonController@raw');

Route::post('form/data/{type}', 'FormController@data');
Route::post('form/check/{type}', 'FormController@check');
Route::post('form/search/{type}', 'FormController@search');
