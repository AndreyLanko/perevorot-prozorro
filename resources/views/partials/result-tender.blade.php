<div class="items-list">
	<div class="container">
		<div class="items-list--item clearfix">
			<div class="row clearfix">
				<div class="col-md-8">
					{{--dump($item)--}}
                    <a href="/tender/{{$item->tenderID}}/" class="items-list--header"><i class="sprite-{{$item->__icon}}-icon"></i><span class="cell">{{!empty($item->title) ? $item->title : trans('facebook.tender_no_name')}}</span></a>
					<div class="clearfix"></div>
					<ol class="breadcrumb">
						<li>{{$item->__icon=='pen' ? trans('tender.pen') : trans('tender.online')}}</li>
						<li class="marked">{{!empty($dataStatus[$item->status]) ? $dataStatus[$item->status] : trans('tender.nostatus')}}</li>
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
									<span>{{trans('interface.expand')}}</span>
									<span>{{trans('interface.collapse')}}</span>
								</a>
							@endif
						</div>
					@endif
					@if (!empty($item->procuringEntity->name))
						<div class="items-list-item-description">
							<strong>{{trans('interface.company')}}:</strong> {{$item->procuringEntity->name}}
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
						<span class="price-description">{{trans('tender.wait_sum')}}</span>
						{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}}
						<span class="uah">{{$item->value->currency}}</span>
					</div>
					@if (!empty($item->enquiryPeriod->startDate))
						<div class="items-list--item--date"><strong>{{trans('tender.start_date')}}:</strong> {{date('d.m.Y', strtotime($item->enquiryPeriod->startDate))}}</div>
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