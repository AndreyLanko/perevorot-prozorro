<div class="items-list">
	<div class="container">
		<div class="items-list--item clearfix">
			<div class="row clearfix">
				<div class="col-md-8">
					{{--dump($item)--}}
					<a href="/tender/{{$item->tenderID}}/" class="items-list--header"><i class="sprite-mouse-icon"></i><span class="cell">{{$item->title}}</span></a>
					<div class="clearfix"></div>
					<ol class="breadcrumb">
						<li>Prozorro</li>
						<li class="marked">{{$dataStatus[$item->status]}}</li>
						@if (!empty($item->procuringEntity->address->locality))
							<li>{{$item->procuringEntity->address->locality}}</li>
						@endif
					</ol>
					@if (!empty($item->description))
						<div class="description-wr{{mb_strlen($item->description)>350?' croped':' open'}}">
							@if ($item->description)
								<div class="description"><p>{{$item->description}}</p></div>
							@endif
							@if (mb_strlen($item->description)>350)
								<a class="search-form--open" href="">
									<i class="sprite-arrow-right"></i>
									<span>розгорнути</span>
									<span>згорнути</span>
								</a>
							@endif
						</div>
					@endif
					@if (!empty($item->procuringEntity->name))
						<div class="items-list-item-description">
							<strong>Компанія:</strong> {{$item->procuringEntity->name}}
						</div>
					@endif
					<div class="items-list--tem-id"><strong>ID:</strong> {{$item->tenderID}}</div>
				</div>
				<div class="col-md-4 relative">	
					{{--
					<a href="" title="Add to favorite" class="favorite"><i class="sprite-star">Favorite icon</i></a>
					<a href="" title="Delete" class="price-delete"><i class="sprite-close-blue">Delete</i></a>
					--}}
					<div class="items-list--item--price">
						<span class="price-description">Очікувана вартість</span>
						{{number_format($item->value->amount, 0, '', ' ')}}
						<span class="uah">{{$item->value->currency}}</span>
					</div>
					@if (!empty($item->enquiryPeriod->startDate))
						<div class="items-list--item--date"><strong>Оголошено:</strong> {{date('d.m.Y', strtotime($item->enquiryPeriod->startDate))}}</div>
					@endif
				</div>
			</div>
			{{--
			<div class="breadcrumb_custom flat">
				<a href="#" class="disable"><strong>Створена:</strong> ?27.07 (сб)</a>
				<a href="#" class="active"><strong>Уточнення:</strong> ?до 29.06 (пн)</a>
				<a href="#"><strong>Пропозиції:</strong> ?до 6.07 (пт)</a>
				<a href="#"><strong>Аукціон:</strong> ?12.07 (пт)</a>
				<a href="#"><strong>Кваліфікаця:</strong> ?з 15.07 (пн)</a>
			</div>
			--}}
		</div>
	</div>
</div>