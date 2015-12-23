<a class="search-form--save" href=""><i class="sprite-arrow-right"></i> Зберегти пошуковий запит</a>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="search-form--filter">
				<div class="result-all">{{number_format($total, 0, '', ' ')}} підходящі закупівлі</div>
				<ul class="nav navbar-nav inline-navbar search-form--filter--filter-records">
					<li><a href="">Додані сьогодні</a> (?)</li>
					<li><a href="">Уточнення</a> (?)</li>
					<li><a href="">Подання пропозицій</a> (?)</li>
					<li><a href="">Аукціон</a> (?)</li>
					<li><a href="">Кваліфікація</a> (?)</li>
					<li><a href="">Завершені</a> (?)</li>
				</ul>
			</div>
		</div>
		<div class="col-md-4">
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
		</div>
	</div>
</div>

@include('partials.result')