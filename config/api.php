<?php

    $api=[
        'tender'=>env('API_TENDER'),
        'plan'=>env('API_PLAN')
    ];
    
    if(env('APP_ENV')=='local')
    {
        $api['__switcher']['tender']=[
            	'prozorro'=>env('API_TENDER'),
            'v2'=>'http://v20.aws3.tk/search',
            	'sandbox'=>'http://sandbox.aws3.tk/search',
            //'ocds'=>'http://ocds-test.aws3.tk/search',
            	//'merged'=>'http://merged.aws3.tk/search'
        ];

        $api['__switcher']['plan']=[
            	'plan'=>env('API_PLAN'),
            	'plan2'=>'http://plans20.aws3.tk/search'
        ];

        $api['__switcher']['pmtype']=[
            'all'=>'',
            'below'=>'belowThreshold',
            'aboveUA'=>'aboveThresholdUA',
            'aboveEU'=>'aboveThresholdEU',
            'reporting'=>'reporting',
            'negotiation'=>'negotiation',
            'neg.quick'=>'negotiation.quick',
        ];
    }
    
    return $api;
