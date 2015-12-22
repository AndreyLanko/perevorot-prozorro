@extends('layouts/app')

@section('content')

@include('partials/form')

@if ($item && !$error)
	<div style="padding:20px 20px 40px 10px;">
		<h1>{{$item->title}}</h1>
		<div><b>{{$item->tenderID}}</b></div>
		<div><i>{{$item->description}}</i></div>
		<div>{{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->tenderPeriod->endDate))}}</div>
		<h4>{{$item->value->amount}} {{$item->value->currency}}</h4>
	</div>
@elseif ($error)
	<div style="padding:20px 20px 40px 10px;">
		API ERROR: {{$error}}
	</div>
@else
	<div style="padding:20px 20px 40px 10px;">
		Тендер не знайдено
	</div>
@endif