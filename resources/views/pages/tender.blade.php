@extends('layouts/app')

@section('content')

@include('partials/form')
@if ($item)
	<div style="padding:20px 20px 40px 10px;">
		<h1>{{$item->_source->title}}</h1>
		<div><b>{{$item->_source->tenderID}}</b></div>
		<div><i>{{$item->_source->description}}</i></div>
		<div>{{date('d.m.Y', strtotime($item->_source->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->_source->tenderPeriod->endDate))}}</div>
		<h4>{{$item->_source->value->amount}} {{$item->_source->value->currency}}</h4>
	</div>
@else
	<div style="padding:20px 20px 40px 10px;">
		Тендер не знайдено
	</div>
@endif