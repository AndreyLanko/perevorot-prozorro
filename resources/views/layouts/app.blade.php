<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="csrf-token" content="{{csrf_token()}}" />
	<title>Prozorro</title>
	<link rel="stylesheet" href="/assets/css/app.css">
	<link rel="stylesheet" href="/assets/css/site.css">
	<!--[if lt IE 9]>
		<script src="/assets/js/legacy/html5shiv.min.js"></script>
		<script src="/assets/js/legacy/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default top-menu">
	    <div class="container">
	        <ul class="nav navbar-nav pull-right inline-navbar">
	            <li><a href=""><i class="sprite-social-fb"></i></a></li>
	            <li><a href=""><i class="sprite-social-tw"></i></a></li>
	            <li><a href=""><i class="sprite-social-g"></i></a></li>
	            <li><a href=""><i class="sprite-social-in"></i></a></li>
	            <li><a href="">Почати роботу</a></li>
	        </ul>
	    </div>
	</nav>
	<nav class="navbar navbar-default main-menu">
	    <div class="container">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="/"><img src="/assets/images/logo.jpg" width="280" height="88" alt="Logo"></a>
	        </div>
	
			<div class="clearfix visible-sm"></div>
			<div class="main-menu--list">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav nav-justified">
						<li><a href="#">ПОСТАЧАЛЬНИКУ</a></li>
						<li><a href="#">ЗАМОВНИКУ</a></li>
						<li><a href="#">ПРО РЕФОРМУ</a></li>
						<li><a href="#">МОНІТОРИНГ</a></li>
						<li><a href="#">ПІДТРИМКА</a></li>
						<li><a href="#">НОВИНИ</a></li>
						<li><a href="#">КОНТАКТИ</a></li>
					</ul>
				</div>
			</div>
	    </div>
	</nav>

	@yield('content')

	<script src="/assets/js/app.js"></script>
</body>
</html>