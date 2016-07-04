<?php

	foreach(Config::get('locales.languages') as $language)
	{
		$prefix=(Config::get('locales.default')==$language ? '' : $language.'/');

		Route::group(['prefix' => $prefix], function()
		{
            Route::get('/', 'PageController@home');
            
            Route::get('search', 'PageController@search_redirect');
            Route::get('{search}/search', 'PageController@search');
            Route::get('plan/search/print/{print}', 'PrintController@plan_list')->where('print', '(html)');;
            
            Route::get('tender/{id}', 'PageController@tender');
            Route::get('plan/{id}', 'PageController@plan');

            Route::post('form/data/{type}', 'FormController@data');
            Route::post('{search}/form/check/{type}', 'FormController@check');            
            Route::post('{search}/form/search', 'FormController@search');
            Route::post('form/autocomplete/{type}', 'FormController@autocomplete');            

            Route::get('tender/{id}/print/{type}/{print}', 'PrintController@one')->where('print', '(pdf|html)');
            Route::get('tender/{id}/print/{type}/{print}/{lot_id?}', 'PrintController@one')->where('print', '(pdf|html)');

            #Route::get('{url}', 'ErrorController@notfound');
            #Route::get('error/404', 'ErrorController@notfound');
            #Route::get('error/500', 'ErrorController@systemerror');
		});
	}

    Route::post('feedback', 'FeedbackController@store');

    Route::get('json/platforms/{type}', 'JsonController@platforms');
