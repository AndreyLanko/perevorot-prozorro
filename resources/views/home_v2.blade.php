@extends('app')

@section('content')

<div class="wrapper">

	<div class="blocks-wr">
		<div id="blocks" class="blocks clearfix">
			<input id="query" class="query_input no_blocks" type="text" autocomplete="off" data-js="form" data-placeholder="Назва товару, код товару, назва або ЄДРПОУ компанії, дата або що завгодно ще...">
			<a href="" class="more">Шукати</a>
		</div>
		<div id="suggest" class="suggest"></div>
	</div>
	
</div>
<div id="buttons" class="buttons"></div>

<input type="text" id="server_query" style="position:fixed;left:0px;bottom:0px;width:100%">

<div id="result" style="display:none;padding:70px 20px 40px 20px"></div>


<script id="helper-suggest" type="text/x-jquery-tmpl">
	<div class="none"><a href>{name}: <span class="highlight">{value}</span></a></div>
</script>

<script id="helper-button" type="text/x-jquery-tmpl">
	<button>{name}</button>
</script>

<script id="block-query" type="text/x-jquery-tmpl">
	<div class="block block-query">
		<span class="block-key">Ключове слово</span>{value}
	</div>
</script>

<script id="block-cpv" type="text/x-jquery-tmpl">
	<div class="block block-cpv">
		<button class="none">Вибрати&nbsp;(<span></span>)</button>
		<span class="block-key">CPV</span><select />
	</div>
</script>

<script id="block-dkpp" type="text/x-jquery-tmpl">
	<div class="block block-dkpp">
		<span class="block-key">ДКПП</span><select />
	</div>
</script>

<script id="block-date" type="text/x-jquery-tmpl">
	<div class="block block-date dateselect">
		<a href class="block-date-arrow">▼</a>
		<div class="block-date-tooltip none">
			<div>Період проведення торгів</div>
			<div>Період прийому пропозицій</div>
		</div>
		<span class="block-key"></span>
		<div class="block-date-picker">
			<input class="date start" type="text">—<input class="date end" class="text">
		</div>
	</div>
</script>

<script id="block-edrpou" type="text/x-jquery-tmpl">
	<div class="block block-edrpou">
		<span class="block-key">Замовник</span><select />
	</div>
</script>

<script id="block-region" type="text/x-jquery-tmpl">
	<div class="block block-region">
		<span class="block-key">Регіон</span><select />
	</div>
</script>

<script id="block-proceduretype" type="text/x-jquery-tmpl">
	<div class="block block-proceduretype">
		<span class="block-key">Тип процедури</span><select />
	</div>
</script>

<script id="block-status" type="text/x-jquery-tmpl">
	<div class="block block-status">
		<span class="block-key">Статус</span><select />
	</div>
</script>

<script id="block-tid" type="text/x-jquery-tmpl">
	<div class="block block-status">
		<span class="block-key">№ закупівлі</span><select />
	</div>
</script>

@endsection
