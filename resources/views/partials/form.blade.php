<div class="search-form">	
	<div class="main-search">
		<div class="container">		
			{{--
				<h1>Пошук тендера</h1>
				<div class="search-form--category">
					<ul class="nav navbar-nav inline-navbar">
						<li><a class="active" href="">Тендери</a></li>
						<li><a href="">Плани закупівлі</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
			--}}
			
			<div class="blocks-wr">
				<div id="blocks" class="blocks clearfix">
					<input id="query" class="query_input no_blocks" type="text" autocomplete="off" data-js="form" data-lang="{{Config::get('locales.href')}}" data-no-results="{{Config::get('locales.href')}}" data-placeholder="{{trans('form.no_results')}}"@if (!empty($preselected_values)) data-preselected='{{$preselected_values}}'@endif @if (!empty($preselected_values)) data-highlight='{{$highlight}}'@endif>
					<button id="search_button" class="more" disabled></button>
				</div>
				<div id="suggest" class="suggest"></div>
			</div>
			<div class="search-form--filter mob-visible none-important" mobile-totals>
				<div class="result-all"><a href="" class="result-all-link">{{trans('form.resuts_found')}} <span></span>. {{trans('form.resuts_show')}}</a></div>
            </div>
			<div class="search-form--add-cryteria">
				<div class="nav navbar-nav inline-navbar">
					<div id="buttons" class="buttons"></div>
				</div>
				{{--
				    <a href="" class="pull-right">Як користуватись пошуком?</a>
				--}}
			</div>
		</div>
	</div>
	<div class="main-result" data-js="search_result">	
		<div id="result" class="result">
			@if (!empty($result))
				{!!$result!!}
			@endif
		</div>
	</div>
</div>

<script id="helper-suggest" type="text/x-jquery-tmpl">
<div class="none"><a href>{name}: <span class="highlight">{value}</span></a></div>
</script>

<script id="helper-button" type="text/x-jquery-tmpl">
<button>{name}</button>
</script>

<script id="block-query" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.keyword')}}" data-button-name="{{trans('form.keyword_short')}}">
<div class="block block-query"><span class="block-key">{{trans('form.keyword_short')}}</span><input type="text" value="{value}"></div>
</script>

<script id="block-cpv" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.cpv')}}" data-button-name="{{trans('form.cpv')}}">
<div class="block block-cpv"><button class="none">{{trans('form.choose')}}&nbsp;(<span></span>)</button><span class="block-key">{{trans('form.cpv')}}</span><select /></div>
</script>

<script id="block-dkpp" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.dkpp')}}" data-button-name="{{trans('form.dkpp')}}">
<div class="block block-dkpp"><button class="none">{{trans('form.choose')}}&nbsp;(<span></span>)</button><span class="block-key">{{trans('form.dkpp')}}</span><select /></div>
</script>

<script id="block-date" type="text/x-jquery-tmpl" data-types='{!!json_encode(trans('form.date_types'), JSON_UNESCAPED_UNICODE)!!}' data-button-name="{{trans('form.date')}}">
<div class="block block-date dateselect"><a href class="block-date-arrow"></a><div class="block-date-tooltip"></div><span class="block-key"></span><div class="block-date-picker"><input class="date start" type="text">—<input class="date end" class="text"></div></div>
</script>

<script id="block-edrpou" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.customer')}}" data-button-name="{{trans('form.customer')}}">
<div class="block block-edrpou"><span class="block-key">{{trans('form.customer')}}</span><select /></div>
</script>

<script id="block-region" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.region')}}" data-button-name="{{trans('form.region')}}">
<div class="block block-region"><span class="block-key">{{trans('form.region')}}</span><select /></div>
</script>

<script id="block-procedure" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.type')}}" data-button-name="{{trans('form.type')}}">
<div class="block block-procedure"><span class="block-key">{{trans('form.type')}}</span><select /></div>
</script>

<script id="block-status" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.status')}}" data-button-name="{{trans('form.status')}}">
<div class="block block-status"><span class="block-key">{{trans('form.status')}}</span><select /></div>
</script>

<script id="block-tid" type="text/x-jquery-tmpl" data-suggest-name="{{trans('form.tenderid')}}" data-button-name="{{trans('form.tenderid')}}">
<div class="block block-tid"><span class="block-key">{{trans('form.tenderid')}}</span><input type="text" value="{value}"></div>
</script>
