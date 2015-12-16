@foreach ($items as $item)
	<div style="border-bottom:1px solid #CCC;padding:20px 0px 20px 0px;">
		<h4><a href="/tender/{{$item->_source->tenderID}}/">{{$item->_source->title}}</a></h4>
		<div><b>{{$item->_source->tenderID}}</b></div>
		<div><i>{{$item->_source->description}}</i></div>
		<div>{{date('d.m.Y', strtotime($item->_source->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->_source->tenderPeriod->endDate))}}</div>
		<h4>{{$item->_source->value->amount}} {{$item->_source->value->currency}}</h4>
		{{--dump($item->_source)--}}
	</div>
@endforeach