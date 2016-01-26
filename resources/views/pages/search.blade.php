@extends('layouts/app')

@section('html_header')
	{!!$html['header']!!}
	{!!$html['switcher']!!}
@endsection

@section('html_footer')
	{!!$html['footer']!!}
@endsection

@section('content')

@include('partials/form')

@endsection
