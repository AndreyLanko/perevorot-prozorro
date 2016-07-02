@extends('layouts/app')

@section('head')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{trans('facebook.site_name')}}">
    <meta property="og:title" content="{{trans('facebook.title')}}">
    <meta property="og:url" content="{{trans('facebook.site_url')}}{{Request::path()}}">
    <meta property="og:image" content="{{trans('facebook.site_url')}}/assets/images/social/fb.png">
    <meta property="og:description" content="{{htmlentities(trans('facebook.description'), ENT_QUOTES)}}">
@endsection

@section('html_header')
    {!!$html['header']!!}
    {!!$html['popup']!!}
@endsection

@section('html_footer')
    {!!$html['footer']!!}
@endsection

@section('content')

<div class="container">
    <h1 class="homepage size48 margin-bottom margin-top-x">Сторінка не знайдена</h1>
</div>

@endsection