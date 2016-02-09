@if($error)
	{{$error}}
@else
	@if ($start<=Config::get('prozorro.page_limit'))
		{{--<div class="search-form-wr"><a class="search-form--save" href=""><i class="sprite-arrow-right"></i>Зберегти пошуковий запит</a></div>--}}
		
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="search-form--filter">
						<div class="result-all">Знайдено закупівель: {{number_format($total, 0, '', ' ')}}</div>
						{{--
						<ul class="nav navbar-nav inline-navbar search-form--filter--filter-records">
							<li><a href="">Додані сьогодні</a> (?)</li>
							<li><a href="">Уточнення</a> (?)</li>
							<li><a href="">Подання пропозицій</a> (?)</li>
							<li><a href="">Аукціон</a> (?)</li>
							<li><a href="">Кваліфікація</a> (?)</li>
							<li><a href="">Завершені</a> (?)</li>
						</ul>
						--}}
					</div>
				</div>
				<div class="col-md-4">
					{{--
						<ul class="nav navbar-nav inline-navbar search-form--filter--show-type">
							<li>Показати:</li>
							<li><a href="" class="active">Детально</a></li>
							<li><a href="">Списком</a></li>
						</ul>
						<div class="clearfix"></div>
						<select>
							<option>Спочатку новіші</option>
							<option>Спочатку новіші</option>
						</select>
					--}}
				</div>
			</div>
		</div>
	@endif

	@foreach ($items as $item)
		@include('partials.result')
	@endforeach

	@if($start<$total)
		<button class="show-more" data-start="{{$start}}">Показати більше результатів {{number_format($start+1, 0, '', ' ')}} — {{($start+Config::get('prozorro.page_limit'))>$total ? number_format($total, 0, '', ' ') : number_format($start+Config::get('prozorro.page_limit'), 0, '', ' ')}} з {{number_format($total, 0, '', ' ')}}</button>
	@endif
@endif