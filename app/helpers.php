<?php

    function href($url='')
	{
    	    $url=parse_url($url);

        return Config::get('locales.href')
                .($url['path']!='/' ? trim($url['path'], '/').'/' : '')
                .(!empty($url['query']) ? '?'.$url['query'] : '');
	}