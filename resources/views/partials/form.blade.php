<div class="search-form">	
	<div class="main-search">
		<div class="container">		
			<h1>Пошук тендера</h1>
			<div class="search-form--category">
				<ul class="nav navbar-nav inline-navbar">
					<li><a class="active" href="">Тендери</a></li>
					<li><a href="">Планові закупівлі</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			
			<div class="blocks-wr">
				<div id="blocks" class="blocks clearfix">
					<input id="query" class="query_input no_blocks" type="text" autocomplete="off" data-js="form" data-placeholder="Назва товару, код товару, назва або ЄДРПОУ компанії, дата або що завгодно ще..."@if (!empty($preselected_values)) data-preselected='{{$preselected_values}}'@endif>
					<button id="search_button" class="more" disabled></button>
				</div>
				<div id="suggest" class="suggest"></div>
			</div>
			<div class="search-form--add-cryteria">
				<div class="nav navbar-nav inline-navbar">
					<div id="buttons" class="buttons"></div>
				</div>
				<a href="" class="pull-right">Як прискорити роботу?</a>
			</div>
		</div>
	</div>
	<div class="main-result" data-js="search_result">	
		<div id="result" class="result">@if (!empty($result)){!!$result!!}@endif</div>
	</div>
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
