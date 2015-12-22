@foreach ($items as $item)
	<div style="border-bottom:1px solid #CCC;padding:20px 0px 20px 0px;">
		<h4><a href="/tender/{{$item->tenderID}}/">{{$item->title}}</a></h4>
		<div><b>{{$item->tenderID}}</b></div>
		<div><i>{{$item->description}}</i></div>
		@if (!empty($item->tenderPeriod->startDate))
			<div>{{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->tenderPeriod->endDate))}}</div>
		@endif
		<h4>{{$item->value->amount}} {{$item->value->currency}}</h4>
		{{--dump($item)--}}
	</div>
@endforeach