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
    
    @if(sizeof(Config::get('api'))>1)
        <style>
            .api-switcher input[disabled]{
                opacity: .5;
            }
            @media (max-width: 769px) {
                .api-switcher{
                    display: none;
                }
            }
        </style>

        <div class="api-switcher" style="position:fixed;font-size:10px;top:7px;left:7px;z-index:2222222">
            <form action="/" method="get">
                @foreach(Config::get('api') as $api=>$url)
                    <input type="submit" name="{{$api}}" value="{{$api}}"{{Session::get('api')==$url ? ' disabled':''}}>
                @endforeach
            </form>
        </div>
    @endif

    <div class="wrapper-main">
        
        @yield('html_header')

        @yield('content')
        <div class="last"></div>
    </div>
    
    @yield('html_footer')
    
    <script src="/assets/js/app.js"></script>
</body>
</html>