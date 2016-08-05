<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>{{env('HTML_TITLE', '')}}Prozorro</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/site.css">
    <link rel="stylesheet" href="/pz-wp/wp-content/themes/Prozzoro/css/search.css">
    <!--[if lt IE 9]>
        <script src="/assets/js/legacy/html5shiv.min.js"></script>
        <script src="/assets/js/legacy/respond.min.js"></script>
    <![endif]-->
    <link rel='shortcut icon' type='image/x-icon' href='/assets/images/favicon.ico' />
    @yield('head')
</head>
<body>
    @if (env('GA_CODE'))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            
            ga('create', '{{env('GA_CODE')}}', 'auto');
            ga('send', 'pageview');
        </script>
    @endif

    @if (env('GTA_CODE'))
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id={{env('GTA_CODE')}}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{env('GTA_CODE')}}');</script>
    @endif

    <div class="wrapper-main">
        
        @yield('html_header')

		<div class="container lang-box hidden-xs">
			<ul class="language-chooser language-chooser-text qtranxs_language_chooser" id="qtranslate-chooser">
				<li class="lang-en{{Config::get('locales.current')=='en'?' active':''}}">
					<a href="/en/"><span>Eng</span></a>
				</li>
				<li class="lang-ua{{Config::get('locales.current')=='ua'?' active':''}}">
					<a href="/"><span>Укр</span></a>
				</li>
			</ul>
		</div>
		
        @yield('content')
        <div class="last"></div>

        @include('forms/feedback')
    </div>

    
    @yield('html_footer')
    
    <script src="/assets/js/app.js"></script>
    @if (env('YAMETRIC_CODE'))
        <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter{{env('YAMETRIC_CODE')}} = new Ya.Metrika({ id:{{env('YAMETRIC_CODE')}}, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/{{env('YAMETRIC_CODE')}}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    @endif
</body>
</html>