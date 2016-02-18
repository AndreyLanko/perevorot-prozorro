@extends('layouts/app')

@section('head')
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="prozorro.org">
	<meta property="og:title" content="PROZORRO">
	<meta property="og:url" content="{{Request::root()}}/{{Request::path()}}">
	<meta property="og:image" content="{{Request::root()}}/assets/images/social/fb.png">
	<meta property="og:description" content="{{htmlentities('ProZorro – пілотний проект електронної системи публічних закупівель, що дозволяє онлайн продавати Державі.', ENT_QUOTES)}}">
@endsection

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

<a href="" class="go-down hidden-xs hidden-xm" data-js="go_up_down"></a>
<a href="" class="back-to-top hidden-xs hidden-xm"></a>

@include('partials/form')

<div class="container" homepage>
	<h1 class="homepage size48 margin-bottom margin-top-x">ProZorro – пілотний проект електронної системи публічних закупівель, що дозволяє онлайн продавати Державі.</h1>
    
    <div class="description">
        <div class="text">
            <div class="text-itself">
                <p>Система електронних закупівель працює з лютого 2014 року, за цей час майже 3000 державних замовників оголосило більше 50 тисяч торгів. На підставі розпорядження Кабінетом Міністрів України ​№1408-р від 25.12.2015р. ProZorro є пілотним проектом, державним замовникам рекомендовано використовувати систему для допорогових закупівель товарів.</p>
                <p>Наразі Верховна Рада України ухвалила проекту закону "Про Публічні Закупівлі", згідно якого протягом 2016 року всі державні закупівлі будуть переведені в електронну систему ProZorro. З 1 квітня дія закону "Про Публічні Закупівлі" поширюється на центральні органи виконавчої влади та закупівельників відповідно до Закону "Про особливості здійснення державних закупівель в окремих сферах господарської діяльності", а з 1 серпня на всіх інших закупівельників.</p>
                <p>Пілотний проект, який створили волонтери з майдану та прийшли з ним в міністерство економічного розвитку і торгівлі України вже перетворився на Закон та нову систему закупівель</p>
            </div>
        </div>
        <div class="switcher" data-js="home_more">
            	<a href="" class="more2 margin-bottom-x">Докладніше</a>
            	<a href="" class="more2 margin-bottom-x">Згорнути</a>
        </div>
    </div>
	
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
											{{--
    											@if (!empty($item->numberOfBids))
    												Участників: {{$item->numberOfBids}}<br />
    											@endif
    											Поточна ставка: <span class="marked">? грн</span><br />
											--}}
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

	<a href="/monitoryng/">
        	<table class="center size18 table-sm line-height1 valign-top table-monitor" width="100%">
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
	</a>
	
	<hr class="margin-bottom-x mob-hide" />
	
	<h1 class="size48 margin-bottom-x mob-hide">Рейтинг замовників</h1>
	
	<div class="center table-monitor mob-hide">
		<img src="http://bi.prozorro.org/images/000001_QkJVDL.png" >
		{{--<img src="images/chart.jpg" alt="Chart" />--}}
	</div>
	
</div>

@endsection