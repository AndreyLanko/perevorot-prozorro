<?php

    $api=[
        'tender'=>env('API_TENDER_PROZORRO'),
        'plan'=>env('API_PLAN')
    ];
    
    if(env('APP_ENV')=='local')
    {
        $api['__switcher']['tender']=[
            	'prozorro'=>env('API_TENDER_PROZORRO'),
            'v24'=>env('API_TENDER_V2'),
            	'23'=>env('API_TENDER_SANDBOX'),
        ];

        $api['__switcher']['plan']=[
            	'plan'=>env('API_PLAN'),
            'plan24'=>env('API_PLAN_V2'),
            	'plan23'=>env('API_PLAN2')
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
