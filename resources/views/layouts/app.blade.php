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
	@yield('head')
</head>
<body>
	<div class="wrapper-main">
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
		            <a class="navbar-brand" href="/"><img src="/assets/images/logo.svg" width="280" height="88" alt="Logo"></a>
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
		<div class="last"></div><!--for footer-->
	</div>
	<nav class="navbar navbar-default footer">
	    <div class="container">
			<div class="row">
				<div class="col-md-3 margin-bottom">
					<h4>ПРО ПРОЕКТ</h4>
					
					<ul class="nav nav-list">
						<li><a href="#">Приєднатись до команди</a></li>
						<li><a href="#">Мета та принципи реформи</a></li>
						<li><a href="#">Паспорт реформи</a></li>
						<li><a href="#">Хід реформи</a></li>
						<li><a href="#">Учасники проекту</a></li>
						<li><a href="#">Питання та відповіді</a></li>
					</ul>
				</div>
				
				<div class="col-md-3 margin-bottom">
					<h4>ДЛЯ НОВИХ УЧАСНИКІВ</h4>
					
					<ul class="nav nav-list margin-bottom">
						<li><a href="#">Замовнику</a></li>
						<li><a href="#">Постачальнику</a></li>
					</ul>
					
					<h4>ДЛЯ РОЗРОБНИКІВ</h4>
					
					<ul class="nav nav-list">
						<li><a href="#">Новим майданчикам</a></li>
						<li><a href="#">OpenProcurement</a></li>
					</ul>
				</div>
				
				<div class="col-md-3 margin-bottom">
					<h4>ДЛЯ ЗМІ</h4>
					
					<ul class="nav nav-list margin-bottom">
						<li><a href="#">Прес-релізи</a></li>
						<li><a href="#">Прес-служба</a></li>
					</ul>
				</div>
				
				<div class="col-md-3 margin-bottom">
					<h4>КОНТАКТИ</h4>
					
					<ul class="nav nav-list margin-bottom">
						<li>+38 (044) 537-85-96</li>
						<li><a href="#">FEEDBACK@PROZORRO.ORG</a></li>
					</ul>
					
					<ul class="nav navbar-nav nav-justified inline-navbar margin-bottom ">
						<li><a href="#"><i class="sprite-social2-fb"></i></a></li>
						<li><a href="#"><i class="sprite-social2-g"></i></a></li>
						<li><a href="#"><i class="sprite-social2-tw"></i></a></li>
						<li><a href="#"><i class="sprite-social2-in"></i></a></li>
					</ul>
				</div>
			</div>
	        
	    </div>
		<div class="footer--copyright">
		<div class="container">
			© 2015 Prozorro. Всі права захищено.
			<div class="pull-right">Служба підтримки: (044) 537-85-96</div>
			</div>
		</div>
	</nav>
	
	<script src="/assets/js/app.js"></script>
</body>
</html>