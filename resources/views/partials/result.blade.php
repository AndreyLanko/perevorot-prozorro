@foreach ($items as $item)
	{{--<div class="row-result">
		<div class="col-md-8">
			<h4 class="result-title"><a href="/tender/{{$item->tenderID}}/">{{$item->title}}</a></h4>
			<div><b>{{$item->tenderID}}</b></div>
			<div><i>{{$item->description}}</i></div>
		</div>
		<div class="col-md-4">
			<h4 class="amount"><span class="amount-number">{{$item->value->amount}} </span>{{$item->value->currency}}</h4>--}}
			{{--dump($item)--}}
			{{--@if (!empty($item->tenderPeriod->startDate))
				<div class="result-date">{{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->tenderPeriod->endDate))}}</div>
			@endif
		</div>
	</div>--}}
	<div class="items-list">
		<div class="container">
			<div class="items-list--item clearfix">
				<div class="row">
					<div class="col-md-8">
						<a href="#" class="items-list--header"><i class="sprite-mouse-icon"></i> Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
						<ol class="breadcrumb">
						  <li>Допорогові торги</li>
						  <li class="marked">Уточнення</li>
						  <li>м. Київ</li>
						</ol>
						<div class="items-list-item-description">
							<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
						</div>
						<div class="items-list--tem-id"><strong>ID:</strong> 091320001234567890</div>
					</div>
					<div class="col-md-4">	
						<a href="#" title="Add to favorite"><i class="sprite-star">Favorite icon</i></a>
						 &nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" title="Delete"><i class="sprite-close-blue">Delete</i></a>
						<div class="items-list--item--price">30 124 <span class="uah">грн</span></div>
						<div class="items-list--item--date"><strong>Дата:</strong> 12-12-2016</div>
					</div>
				</div>
				<div class="breadcrumb_custom flat">
					<a href="#" class="disable"><strong>Створена:</strong> 27.07 (сб)</a>
					<a href="#" class="active"><strong>Уточнення:</strong> до 29.06 (пн)</a>
					<a href="#"><strong>Пропозиції:</strong> до 6.07 (пт)</a>
					<a href="#"><strong>Аукціон:</strong> 12.07 (пт)</a>
					<a href="#"><strong>Кваліфікаця:</strong> з 15.07 (пн)</a>
				</div>
			</div>
			<div class="items-list--item clearfix">
				<div class="row">	
					<div class="col-md-8">
						<a href="#" class="items-list--header"><i class="sprite-pen-icon"></i> Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
						<ol class="breadcrumb">
						  <li>Допорогові торги</li>
						  <li class="marked">Уточнення</li>
						  <li>м. Київ</li>
						</ol>
						<div class="items-list-item-description">
							<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
						</div>
						<div class="items-list--tem-id"><strong>ID:</strong> 091320001234567890</div>
					</div>
					<div class="col-md-4">	
						<a href="#" title="Add to favorite"><i class="sprite-star">Favorite icon</i></a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" title="Delete"><i class="sprite-close-blue">Delete</i></a>
						<div class="items-list--item--price">30 124 <span class="uah">грн</span></div>
						<div class="items-list--item--date"><strong>Дата:</strong> 12-12-2016</div>
					</div>
				</div>
				<div class="breadcrumb_custom flat">
					<a href="#" class="disable"><strong>Створена:</strong> 27.07 (сб)</a>
					<a href="#" class="active"><strong>Уточнення:</strong> до 29.06 (пн)</a>
					<a href="#"><strong>Пропозиції:</strong> до 6.07 (пт)</a>
					<a href="#"><strong>Аукціон:</strong> 12.07 (пт)</a>
					<a href="#"><strong>Кваліфікаця:</strong> з 15.07 (пн)</a>
				</div>
			</div>
		</div>
	</div>
@endforeach