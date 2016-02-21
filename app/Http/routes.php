<?php

    Route::get('/', 'PageController@home');
    Route::get('search', 'PageController@search');
    Route::get('tender/{id}', 'PageController@tender');
        
    Route::post('form/data/{type}', 'FormController@data');
    Route::post('form/check/{type}', 'FormController@check');
    Route::post('form/search', 'FormController@search');
    Route::post('form/autocomplete/{type}', 'FormController@autocomplete');
    
    Route::get('json/platforms/{type}', 'JsonController@platforms');
    