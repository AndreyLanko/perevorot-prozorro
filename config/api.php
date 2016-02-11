<?php

return env('APP_ENV')=='local'?[
	'prozorro'=>env('PROZORRO_API'),
	'ocds'=>'http://ocds-test.aws3.tk/search',
	'sandbox'=>'http://sandbox.aws3.tk/search'
]:['prozorro'=>env('PROZORRO_API')];