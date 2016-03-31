

@extends('layouts/app')

@section('head')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{trans('facebook.site_name')}}">
    <meta property="og:title" content="{{trans('facebook.title')}}">
    <meta property="og:url" content="{{Request::root()}}/{{Request::path()}}">
    <meta property="og:image" content="{{Request::root()}}/assets/images/social/fb.png">
    <meta property="og:description" content="{{htmlentities(trans('facebook.description'), ENT_QUOTES)}}">
@endsection

@section('html_header')
    {!!$html['header']!!}
    {!!$html['popup']!!}
@endsection

@section('html_footer')
    {!!$html['footer']!!}
@endsection

@section('content')

@include('partials/once/timer-first-april')

<div class="container">        
    <ul class="nav nav-justified sections">
        <li class="green-bg notitle"><a href="{{href('postachalniku')}}"><i class="sprite-provider"></i> <span>{{trans('home.provider')}}</span></a></li>
        <li class="gray-bg notitle"><a href="{{href('/')}}"><i class="sprite-tender-search"></i> <span>{{trans('home.tender_search')}}</span></a></li>
        <li class="blue-bg notitle"><a href="{{href('zamovniku')}}"><i class="sprite-customer"></i> <span>{{trans('home.customer')}}</span></a></li>
    </ul>
</div>

<a href="" class="go-down hidden-xs hidden-xm" data-js="go_up_down"></a>
<a href="" class="back-to-top hidden-xs hidden-xm"></a>

@include('partials/form')

<div class="container" homepage>
    <h1 class="homepage size48 margin-bottom margin-top-x">{{trans('home.welcome_title')}}</h1>
    
    <div class="description">
        <div class="text">
            <div class="text-itself">
                {!!trans('home.welcome')!!}
            </div>
        </div>
        <div class="switcher" data-js="home_more">
            <a href="" class="more2 margin-bottom-x">{{trans('interface.more')}}</a>
            <a href="" class="more2 margin-bottom-x">{{trans('interface.collapse')}}</a>
        </div>
    </div>
    
    <div class="video-list">
	    <div class="video col-md-6">
	        <iframe width="100%" height="315" src="https://www.youtube.com/embed/u1m5q9wnxbk" frameborder="0" allowfullscreen></iframe>
	    </div>
	    <div class="video col-md-6" >
	        <iframe width="100%" height="315" src="https://www.youtube.com/embed/skcfKPXJqvA" frameborder="0" allowfullscreen></iframe>
	    </div>
    </div>
    <div class="clearfix"></div>

    @if(!empty($last->items))
        <h1 class="size48 margin-top">{{trans('home.last_tenders')}}</h1>
        
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
                                            <strong>{{trans('interface.company')}}:</strong> {{str_limit($item->procuringEntity->name, 70)}}
                                        </div>
                                    @endif
                                    <a href="/tender/{{$item->tenderID}}/"><i class="sprite-arrow-right"></i> {{trans('interface.more')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            {{--
            <div class="col-sm-12">
                <a href="{{href('/tender/search/?procedure=open')}}"><i class="sprite-arrow-right"></i> {{trans('home.all_last_tenders')}}</a>
            </div>
            --}}
        </div>
        <div class="clearfix"></div>    
        <hr />
    @endif

    @if($auctions)
        <h1 class="size48 margin-top-x">{{trans('home.active_tenders')}}</h1>
        
        <div class="active-auctions row size14 margin-bottom" data-js="home_equal_height">
            @foreach($auctions as $auction)
                <div class="col-md-4">
                    @foreach($auction as $item)
                        <div class="margin-bottom" block>
                            <div class="gray-bg padding">
                                <div class="table-top-bottom">
                                    <div class="item-top">
                                        <p><a href="/tender/{{$item->tenderID}}/" class="size18">{{str_limit($item->title, 60)}}</a></p>
                                        @if(!empty($item->procuringEntity->identifier->legalName))
                                            <p><b>{{trans('interface.company')}}:</b> {{$item->procuringEntity->identifier->legalName}}</p>
                                        @endif
                                    </div>
                                    <div class="item-bottom">
                                        <div class="item-bottom-cell">
                                            {{trans('interface.start_at')}}: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <hr />
    @endif

    <h1 class="size48">{{trans('home.numbers_title')}}</h1>
    <h2 class="center margin-bottom-x">{{trans('home.numbers_href')}} <a href="http://bi.prozorro.org/" target="_blank">bi.prozorro.org</a></h2>

    <a class="number-href" href="{{href('monitoryng')}}">
        <table class="center size18 table-sm line-height1 valign-top table-monitor" width="100%">
            <tbody>
                <tr>
                    <td>
                        <p>{{trans('home.numbers_tender')}}</p>
                        <span class="blue size48">
                            {{$numbers['number'][0]}}<br />
                            <span class="size24">{{$numbers['number'][1]}}</span>
                        </span>
                    </td>
                    <td>
                        <p>{{trans('home.numbers_sum')}}</p>
                        <span class="blue size48">
                            {{$numbers['sum'][0]}}<br />
                            <span class="size24">{{$numbers['sum'][1]}}</span>
                        </span>
                    </td>
                    <td>
                        <p>{{trans('home.numbers_org')}}</p>
                        <span class="blue size48">
                            {{$numbers['organizer'][0]}}<br />
                            <span class="size24">{{$numbers['organizer'][1]}}</span>
                        </span>
                    </td>
                    <td>
                        <p>{{trans('home.numbers_propositions')}}</p>
                        <span class="blue size48">
                            {{$numbers['bids'][0]}}
                        </span>
                    </td>
                    <td>
                        <p>{{trans('home.numbers_economy')}}</p>
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
    
    <h1 class="size48 margin-bottom-x mob-hide">{{trans('home.rating_title')}}</h1>
    
    <div class="center table-monitor mob-hide">
        <img src="http://bi.prozorro.org/images/000001_QkJVDL.png" >
    </div>
    
</div>

@endsection