<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
	<meta name="csrf-token" content="{{csrf_token()}}" />
	<title>Prozorro</title>
	<link rel="stylesheet" href="/assets/css/app.css">
	<link rel="stylesheet" href="/assets/css/site.css">
	<!--[if lt IE 9]>
		<script src="/assets/js/legacy/html5shiv.min.js"></script>
		<script src="/assets/js/legacy/respond.min.js"></script>
	<![endif]-->
	@yield('head')
</head>
<body>
	{{--
	<div style="position:fixed;font-size:10px;top:0px;right:0px;z-index:2222222">
		<form action="/" method="get">
			@if(Session::get('api')=='http://ocds-test.aws3.tk/search')
				<input type="button" value="ocds-test" disabled>
				<input type="submit" value="prozorro">
				<input type="hidden" value="1" name="api">
			@else
				<input type="submit" value="ocds-test">
				<input type="button" value="prozorro" disabled>
				<input type="hidden" value="2" name="api">
			@endif
		</form>
	</div>
	--}}
	<div class="wrapper-main">
		
		@yield('html_header')

		@yield('content')
		<div class="last"></div>
	</div>
	
	@yield('html_footer')
	
	<script src="/assets/js/app.js"></script>
</body>
</html>