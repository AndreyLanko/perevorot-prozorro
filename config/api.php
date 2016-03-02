<?php

    $api=[
        'tender'=>env('API_TENDER'),
        'plan'=>env('API_PLAN')
    ];
    
    if(env('APP_ENV')=='local')
    {
        $api['__switcher']['tender']=[
            	'prozorro'=>env('API_TENDER'),
            	'ocds'=>'http://ocds-test.aws3.tk/search',
            	'sandbox'=>'http://sandbox.aws3.tk/search',
            	'merged'=>'http://merged.aws3.tk/search'
        ];

        $api['__switcher']['plan']=[
            	'plan'=>env('API_PLAN')
        ];
    }
    
    return $api;