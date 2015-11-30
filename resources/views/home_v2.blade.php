@extends('app')

@section('content')
<style>
	.block span.exact_value{
		display: none;
	}
	.block.exact input.subinput{
		display: none;
	}
	.block.exact span.exact_value{
		display: block;
	}
	.selectize-input{
		overflow: visible;
	}
	.selectize-dropdown.single.subinput{
		min-width:500px;
	}
	.selectize-control.subinput.single{
		min-width:300px;
	}
	.disabled *{
		opacity: .5;
		pointer-events: none;
	}
	
	.block-query{
		background-color: #002F2F !important;
		background-image: none !important;
	}

	.block-cpv,
	.selectize-control .selectize-input .block.block-cpv .selectize-input,
	.selectize-input .block-cpv .selectize-control.subinput .selectize-input.has-items,
	.selectize-input .block-cpv .selectize-control.subinput .selectize-input [data-value]  {
		background-color: #046380 !important;
		background-image: none !important;
	}

	.block-region,
	.selectize-control .selectize-input .block.block-region .selectize-input,
	.selectize-input .block-region .selectize-control.subinput .selectize-input [data-value] {
		background-color: #A7A37E !important;
		background-image: none !important;
	}

	.block-date{
		background-color: #006F82 !important;
		background-image: none !important;
	}
	.block-dkpp,
	.selectize-input .block-dkpp .selectize-control.subinput .selectize-input.has-items,
	.selectize-input .block-dkpp .selectize-control.subinput .selectize-input [data-value] {
		background-color: #b30c0c !important;
		background-image: none !important;
	}
	.query_input{
		position:fixed;
		top:0px;
		left:0px;
		width:100%;
	}
	
	/*
	.block .name{

	}
	.block .selectize-control {

	}
	.block .selectize-control .selectize-input [data-value]{
		background-color: transparent !important;
		background-image: none !important;
		border: none;
		text-shadow: none;
		box-shadow: none;
	}
	.block .selectize-input, .selectize-control.single .selectize-input.input-active{
		background: none !important;
		border: none!important;
		text-shadow: none!important;
		box-shadow: none!important;
	}
	.block .selectize-input input{
		color:#FFF;
	}*/
</style>
<input id="query" class="query_input" type="text" autocomplete="off" data-js="form" placeholder="Назва товару, код товару, назва або ЄДРПОУ компанії, дата або що завгодно ще...">
<div id="suggest" style="display:none;position:fixed;background:#FFF;width:500px;top:60px;left:0px;padding:5px;"></div>
<input type="text" id="server_query" style="position:fixed;left:0px;bottom:0px;width:100%">
<div id="result" style="padding:70px 20px 40px 20px"></div>


<script id="block-suggest" type="text/x-jquery-tmpl">
	<div class="disabled"><a href style="display:inline">{name}: {value}</a><span class="loader"> Loading...</span></div>
</script>

<script id="block-query" type="text/x-jquery-tmpl">
	<div class="block block-query">
		Ключове слово: {value}
		<span class="exact_value">{value}</span>
	</div>
</script>

<script id="block-cpv" type="text/x-jquery-tmpl">
	<div class="block block-cpv autocomplete">
		CPV:<select class="subinput" />
		<span class="exact_value">{value}</span>
	</div>
</script>

<script id="block-region" type="text/x-jquery-tmpl">
	<div class="block block-region">
		Регіон:<select class="subinput" />
		<span class="exact_value">{value}</span>
	</div>
</script>

<script id="block-dkpp" type="text/x-jquery-tmpl">
	<div class="block block-dkpp">
		ДКПП: <select class="subinput" />
		<span class="exact_value">{value}</span>
	</div>
</script>

<script id="block-date" type="text/x-jquery-tmpl">
	<div class="block block-date dateselect">
		<select class="subinput">
			<option>Період проведення торгів</option>
			<option>Період прийому пропозицій</option>
		</select>
		<input type="date" class="subinput">—<input type="date" class="subinput">
		<span class="exact_value">{value}</span>
	</div>
</script>

@endsection
