<div class="margin-bottom position-block">
    <h3>{{trans('tender.items')}}</h3>
    @foreach($item->__items as $one)
        <div class="margin-bottom-more">
	        <div class="row margin-bottom">
	            <div class="col-md-4 col-md-push-8">
	                <div class="padding margin-bottom">
	                    {{!empty($one->quantity)?$one->quantity:''}} @if(!empty($one->unit->code)){{trans('measures.'.$one->unit->code.'.symbol')}}@endif
	                </div>
	            </div>
	            <div class="col-md-8 col-md-pull-4 description-wr{{!empty($one->description) && mb_strlen($one->description)>350?' croped':' open'}}">
	                @if (!empty($one->description))
                        <div class="tender--description--text description">
                            {!!nl2br($one->description)!!}
                        </div>
                        @if (mb_strlen($one->description)>350)
                            <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                <span>{{trans('interface.expand')}}</span>
                                <span>{{trans('interface.collapse')}}</span>
                            </a>
                        @endif
	                @endif
	                @if (!empty($one->classification))
	                    <div class="tender-date">{{trans('tender.cpv')}}: {{$one->classification->id}} — {{$one->classification->description}}</div>
	                @else
	                    <div class="tender-date">{{trans('tender.no_cpv')}}</div>
	                @endif
	                @if(!empty($one->additionalClassifications[0]))
	                    <div class="tender-date">{{trans('tender.dkpp')}}: {{$one->additionalClassifications[0]->id}} — {{$one->additionalClassifications[0]->description}}</div>
	                @else
	                    <div class="tender-date">{{trans('tender.no_dkpp')}}</div>
	                @endif
	
	                @if(!empty($one->deliveryAddress->streetAddress))
        	                <div class="tender-date dark">Доставка: {{$one->deliveryAddress->postalCode}}, {{$one->deliveryAddress->region}}, {{$one->deliveryAddress->locality}}, {{$one->deliveryAddress->streetAddress}}</div>
	                @endif
	                @if(!empty($one->deliveryDate->endDate) || !empty($one->deliveryDate->startDate))
	                    <div class="tender-date dark">Дата доставки:
	                        @if(!empty($one->deliveryDate->startDate)) від {{date('d.m.Y H:i', strtotime($one->deliveryDate->startDate))}}@endif
	                        @if(!empty($one->deliveryDate->endDate)) до {{date('d.m.Y H:i', strtotime($one->deliveryDate->endDate))}}@endif
	                    </div>
	                @elseif(!empty($one->deliveryDate))
	                    <div class="tender-date dark">Дата доставки: {{date('d.m.Y H:i', strtotime($one->deliveryDate))}}</div>
	                @endif
	            </div>
	        </div>
        </div>
    @endforeach
</div>