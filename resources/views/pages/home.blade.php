@extends('layouts/app')

@section('html_header')
	{!!$html['header']!!}
@endsection

@section('html_footer')
	{!!$html['footer']!!}
@endsection

@section('content')

<div class="container">		
	<ul class="nav nav-justified sections ">
		<li class="green-bg notitle"><a href="/postachalniku/"><i class="sprite-provider"></i> <span>Постачальнику</span></a></li>
		<li class="gray-bg notitle"><a href="/"><i class="sprite-tender-search"></i> <span>Пошук тендера</span></a></li>
		<li class="blue-bg notitle"><a href="/zamovniku/"><i class="sprite-customer"></i> <span>Замовнику</span></a></li>
	</ul>
</div>

@include('partials/form')

<div class="container" homepage>
	<h1 class="homepage size48 margin-bottom margin-top-x">ProZorro – пілотний проект електронної системи публічних закупівель, що дозволяє онлайн продавати Державі.</h1>

	<a href="#" class="more2 margin-bottom-x">Докладніше</a>
	
	<div class="video col-md-8 col-md-offset-2">
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/skcfKPXJqvA" frameborder="0" allowfullscreen></iframe>
	</div>
	
	<div class="clearfix"></div>

	@if(!empty($last->items))
		<h1 class="size48 margin-top">Щойно оголошені закупівлі</h1>
		
		<div class="tender--simmilar tender-type2" data-js="home_equal_height">
			<div class="row">
				@foreach($last->items as $i=>$item)
					@if($i<3)
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom" block>
								{{--
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								--}}
								<div class="item--top">
									<div class="green tender--simmilar--item--cost">{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></div>
									<a href="/tender/{{$item->tenderID}}/" class="title">{{str_limit($item->title, 60)}}</a>
								</div>
								<div class="item-bottom">
									<div class="item-bottom-cell">
									{{--<div class="tender--legend">Prozorro <span class="marked">{{!empty($dataStatus[$item->status])?$dataStatus[$item->status]:'nostatus'}}</span>    --}}
									<div class="tender--legend">@if (!empty($item->procuringEntity->address->locality)){{$item->procuringEntity->address->locality}}@endif</div>
									@if (!empty($item->procuringEntity->name))
										<div class="tender--simmilar--text margin-bottom">
											<strong>Компанія:</strong> {{str_limit($item->procuringEntity->name, 70)}}
										</div>
									@endif
									<a href="/tender/{{$item->tenderID}}/"><i class="sprite-arrow-right"></i> Детальніше</a>
									</div>
								</div>
							</div>
						</div>
					@endif
				@endforeach
			</div>
			
			<div class="col-sm-12">
				<a href="/search/?procedure=open"><i class="sprite-arrow-right"></i> Перейти до всіх свіжих заявок</a>
			</div>
		</div>
		<div class="clearfix"></div>	
		<hr />
	@endif

	@if($auctions)
		<h1 class="size48 margin-top-x">Проводяться аукціони</h1>
		
		<div class="active-auctions row size14 margin-bottom" data-js="home_equal_height">
			@foreach($auctions as $auction)
				<div class="col-md-4">
					{{--<h4 class="center">Відбуваються прямо зараз</h4>--}}
					@foreach($auction as $item)
						<div class="margin-bottom" block>
							<div class="gray-bg padding">
								<div class="table-top-bottom">
									<div class="item-top">
										<p><a href="/tender/{{$item->tenderID}}/" class="size18">{{str_limit($item->title, 60)}}</a></p>
										@if(!empty($item->procuringEntity->identifier->legalName))
										<p><b>Компанія:</b> {{$item->procuringEntity->identifier->legalName}}</p>
										@endif
									</div>
									<div class="item-bottom">
										<div class="item-bottom-cell">
											
											@if (!empty($item->numberOfBids))
												Участників: {{$item->numberOfBids}}<br />
											@endif
											{{--Поточна ставка: <span class="marked">? грн</span><br />--}}
											Початок: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
					{{--<a href="#"><i class="sprite-arrow-right"></i> Всі поточні аукціони</a>--}}
				</div>
			@endforeach
		</div>
		<hr />
	@endif

	<h1 class="size48">Відкритий моніторинг державних витрат</h1>
	<h2 class="center margin-bottom-x">Кожен може контролювати систему на <a href="http://bi.prozorro.org/" target="_blank">bi.prozorro.org</a></h2>
	
	<table class="center size18 table-sm line-height1 valign-top margin-bottom-x table-monitor" width="100%">
		<tbody>
			<tr>
				<td>
					<p>#тендерів</p>
					<span class="blue size48">
						{{$numbers['number'][0]}}<br />
						<span class="size24">{{$numbers['number'][1]}}</span>
					</span>
				</td>
				<td>
					<p>Планова сума</p>
					<span class="blue size48">
						{{$numbers['sum'][0]}}<br />
						<span class="size24">{{$numbers['sum'][1]}}</span>
					</span>
				</td>
				<td>
					<p># Організаторів</p>
					<span class="blue size48">
						{{$numbers['organizer'][0]}}<br />
						<span class="size24">{{$numbers['organizer'][1]}}</span>
					</span>
				</td>
				<td>
					<p>Пропозицій на торги</p>
					<span class="blue size48">
						{{$numbers['bids'][0]}}
					</span>
				</td>
				<td>
					<p>Економія</p>
					<span class="blue size48">
						{{$numbers['economy'][0]}}<br />
						<span class="size24">{{$numbers['economy'][1]}}</span>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<hr class="margin-bottom-x" />
	
	<h1 class="size48 margin-bottom-x">Рейтинг замовників</h1>
	
	<div class="center margin-bottom-x table-monitor">
		<img src="http://bi.prozorro.org/images/000002_UjcvFH.png" alt="Chart" >
		{{--<img src="images/chart.jpg" alt="Chart" />--}}
	</div>
	
</div>

@endsection