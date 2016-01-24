@extends('layouts/app')

@section('content')

<div class="container">		
	<ul class="nav nav-justified sections ">
		<li class="green-bg notitle"><a href="http://prozorro.lemon.ua/postachalniku/"><i class="sprite-customer"></i> <span>Постачальнику</span></a></li>
		<li class="gray-bg notitle"><a href="http://prozorro.lemon.ua/poshuk-tendera/"><i class="sprite-tender-search"></i> <span>Пошук тендера</span></a></li>
		<li class="blue-bg notitle"><a href="http://prozorro.lemon.ua/zamovniku/"><i class="sprite-provider"></i> <span>Замовнику</span></a></li>
	</ul>
</div>

@include('partials/form')

<div class="container" homepage>
	<h1 class="size48 margin-bottom margin-top-x">ProZorro – пілотний проект електронної системи публічних закупівель, що дозволяє онлайн продавати Державі.</h1>

	<a href="#" class="more2 margin-bottom-x">Докладніше</a>
	
	<div class="video col-md-8 col-md-offset-2">
		<iframe width="100%" height="315" src="https://www.youtube.com/embed/skcfKPXJqvA" frameborder="0" allowfullscreen></iframe>
	</div>
	
	<div class="clearfix"></div>

	@if(!empty($last->items))
		<h1 class="size48 margin-top">Щойно оголошені закупівлі</h1>
		
		<div class="tender--simmilar tender-type2">
			<div class="row">
				@foreach($last->items as $i=>$item)
					@if($i<3)
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								{{--
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								--}}
								<div class="green tender--simmilar--item--cost">{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></div>
								<a href="/tender/{{$item->tenderID}}/" class="title">{{$item->title}}</a>
								<div class="tender--legend">Prozorro <span class="marked">{{$dataStatus[$item->status]}}</span>    @if (!empty($item->procuringEntity->address->locality)){{$item->procuringEntity->address->locality}}@endif</div>
								@if (!empty($item->procuringEntity->name))
									<div class="tender--simmilar--text margin-bottom">
										<strong>Компанія:</strong> {{$item->procuringEntity->name}}
									</div>
								@endif
								<a href="/tender/{{$item->tenderID}}/"><i class="sprite-arrow-right"></i> Детальніше</a>
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
		<h1 class="size48 margin-top-x">Сьогоднішні аукціони</h1>
		
		<div class="row size14 margin-bottom">
			@foreach($auctions as $auction)
				<div class="col-md-4">
					{{--<h4 class="center">Відбуваються прямо зараз</h4>--}}
					@foreach($auction as $item)
						<div class="margin-bottom">
							<div class="gray-bg padding">
								<p><a href="/tender/{{$item->tenderID}}/" class="size18">{{$item->title}}</a></p>
								участників: {{!empty($item->bids)?sizeof($item->bids):0}}<br />
								Поточна ставка: <span class="marked">? грн</span><br />
								Почався ? год тому
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
					<img src="http://bi.prozorro.org/images/000001_qrrxD.png" alt="" >
					{{--<p>#тендерів</p>
					<span class="blue size48">
						35,21<br />
						<span class="size24">тис</span>
					</span>--}}
				</td>
				<td>
					<img src="http://bi.prozorro.org/images/000001_CanHme.png" alt="" >				
					{{--<p>Планова сума</p>
					<span class="blue size48">
						6,37<br />
						<span class="size24">млрд</span>
					</span>--}}
				</td>
				<td>
					<img src="http://bi.prozorro.org/images/000001_pAuPP.png" alt="" >	
					{{--<p># Організаторів</p>
					<span class="blue size48">
						2,25<br />
						<span class="size24">тис</span>
					</span>--}}
				</td>
				<td>
					<img src="http://bi.prozorro.org/images/000001_eqpLp.png" alt="" >	
					{{--<p>Пропозицій на торги</p>
					<span class="blue size48">
						2,71
					</span>--}}
				</td>
				<td>
					<img src="http://bi.prozorro.org/images/000001_XxALYp.png" alt="" >	
					{{--<p>Економія</p>
					<span class="blue size48">
						498,38<br />
						<span class="size24">млн</span>
					</span>--}}
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