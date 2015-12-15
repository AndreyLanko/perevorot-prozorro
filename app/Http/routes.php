<?php

Route::get('/', 'HomeController@index');

Route::get('json/combined', 'JsonController@combined');
Route::get('json/parsed', 'JsonController@parsed');
Route::get('json/raw', 'JsonController@raw');

Route::post('form/data/{type}', 'FormController@data');
Route::post('form/check/{type}', 'FormController@check');
Route::post('form/search/{type}', 'FormController@search');

Route::get('search', 'HomeController@index');