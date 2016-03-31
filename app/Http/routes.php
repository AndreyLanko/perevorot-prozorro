<?php

	foreach(Config::get('locales.languages') as $language)
	{
		$prefix=(Config::get('locales.default')==$language ? '' : $language.'/');

		Route::group(['prefix' => $prefix], function()
		{
            Route::get('/', 'PageController@home');
            
            Route::get('{search}/search', 'PageController@search');
            
            Route::get('tender/{id}', 'PageController@tender');
            Route::get('plan/{id}', 'PageController@plan');
                
            Route::post('form/data/{type}', 'FormController@data');
            Route::post('{search}/form/check/{type}', 'FormController@check');            
            Route::post('{search}/form/search', 'FormController@search');
            Route::post('form/autocomplete/{type}', 'FormController@autocomplete');            

            Route::get('tender/{id}/print/{type}', 'PrintController@index');
		});
	}

    Route::get('json/platforms/{type}', 'JsonController@platforms');
    