<div class="wrapper">

	<div class="blocks-wr">
		<div id="blocks" class="blocks clearfix">
			<input id="query" class="query_input no_blocks" type="text" autocomplete="off" data-js="form" data-placeholder="Назва товару, код товару, назва або ЄДРПОУ компанії, дата або що завгодно ще..."@if (!empty($preselected_values)) data-preselected='{{$preselected_values}}'@endif>
			<button id="search_button" class="more" disabled>Шукати</button>
		</div>
		<div id="suggest" class="suggest"></div>
	</div>
	
</div>
<div id="buttons" class="buttons"></div>

<input type="text" id="server_query" style="position:fixed;left:0px;bottom:0px;width:100%">

<div id="result" style="padding:20px 20px 40px 10px;">
</div>


<script id="helper-suggest" type="text/x-jquery-tmpl">
	<div class="none"><a href>{name}: <span class="highlight">{value}</span></a></div>
</script>

<script id="helper-button" type="text/x-jquery-tmpl">
	<button>{name}</button>
</script>

<script id="block-query" type="text/x-jquery-tmpl">
	<div class="block block-query">
		<span class="block-key">Ключове слово</span><input type="text" value="{value}">
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
		<div class="block-date-tooltip"></div>
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

<script id="block-procedure" type="text/x-jquery-tmpl">
	<div class="block block-procedure">
		<span class="block-key">Тип процедури</span><select />
	</div>
</script>

<script id="block-status" type="text/x-jquery-tmpl">
	<div class="block block-status">
		<span class="block-key">Статус</span><select />
	</div>
</script>

<script id="block-tid" type="text/x-jquery-tmpl">
	<div class="block block-tid">
		<span class="block-key">№ закупівлі</span><input type="text" value="{value}">
	</div>
</script>