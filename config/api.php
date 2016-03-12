<?php

return env('APP_ENV')=='local'?[
	'prozorro'=>env('PROZORRO_API'),
	'v2'=>'http://v20.aws3.tk/search',
	'sandbox'=>'http://sandbox.aws3.tk/search',
	//'ocds'=>'http://ocds-test.aws3.tk/search',
	//'merged'=>'http://merged.aws3.tk/search'
]:['prozorro'=>env('PROZORRO_API')];