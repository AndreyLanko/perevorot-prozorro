@extends('layouts/app')

@section('head')
    @if ($item && !$error)
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{trans('facebook.site_name')}}">
        <meta property="og:title" content="{{htmlentities($item->procuringEntity->name, ENT_QUOTES)}}">
        <meta property="og:url" content="{{env('ROOT_URL')}}/{{Request::path()}}">
        <meta property="og:image" content="{{env('ROOT_URL')}}/assets/images/social/fb.png">
        <meta property="og:description" content="{{!empty($item->title) ? htmlentities($item->title, ENT_QUOTES) : trans('facebook.tender_no_name')}}">
    @endif
@endsection

@section('html_header')
    @if (!empty($html))
        {!!$html['header']!!}
        {!!$html['popup']!!}
    @endif
@endsection

@section('html_footer')
    @if (!empty($html))
        {!!$html['footer']!!}
    @endif
    @if(!empty($item->__is_sign))
        <script src="https://rawgit.com/openprocurement-crypto/common/master/js/index.js"></script>
    @endif
@endsection

@section('content')

    @if ($item && !$error)
        <div class="tender" data-js="tender">
    
            @include('partials/blocks/tender/header')
    
            <div class="tender--description">
                <div class="container">
                    <div class="margin-bottom-xl">
                        <div class="row">
                            <div class="col-sm-9">
                                @if(!empty($item->__open_name) && $item->__open_name!='hide')
                                    @if(!empty($item->__open_name))
                                        <h2>{{$item->__open_name}}</h2>
                                    @endif
                                    
                                    @if($item->__print_href && !in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                                        @if(starts_with($item->__print_href, 'limited'))
                                            @if(empty($item->__active_award))
                                                <div style="margin-top:-30px;margin-bottom:40px">Для друку форми необхідно завершити дії на майданчику</div>
                                            @else
                                                <div style="margin-top:-30px;margin-bottom:40px">Друкувати форму оголошення <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/html')}}" target="_blank">HTML</a></div>
                                            @endif
                                        @else
                                            <div style="margin-top:-30px;margin-bottom:40px">Друкувати форму оголошення <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/html')}}" target="_blank">HTML</a></div>
                                        @endif
                                    @endif
                                @else
                                    @if ($item->procurementMethod == 'open' && in_array($item->procurementMethodType, ['aboveThresholdEU', 'competitiveDialogueEU', 'aboveThresholdUA.defense']) && ($item->procurementMethodType == 'aboveThresholdUA.defense' && !empty($item->title_en)))
                                        @if (Lang::getLocale() == 'en' )
                                            <h2>Tender notice</h2>
                                        @else
                                            <h2></h2>
                                        @endif
                                    @endif
                                @endif

                                @if ($item->__isSingleLot)
                                    @if(in_array($item->status, ['complete', 'unsuccessful', 'cancelled']) && $item->procurementMethod=='open' && in_array($item->procurementMethodType, ['aboveThresholdUA', 'aboveThresholdEU', 'aboveThresholdUA.defense']))
                                        <div style="margin-top:-30px;margin-bottom:40px">Друкувати звіт про результати проведення процедури <a href="{{href('tender/'.$item->tenderID.'/print/report/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/report/html')}}" target="_blank">HTML</a></div>
                                    @endif
                                    @if(in_array($item->status, ['complete', 'cancelled']) && $item->procurementMethod=='limited' && in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                                        <div style="margin-top:-30px;margin-bottom:40px">Друкувати звіт про результати проведення процедури <a href="{{href('tender/'.$item->tenderID.'/print/report/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/report/html')}}" target="_blank">HTML</a></div>
                                    @endif
                                @endif


    
                                {{--Інформація про замовника--}}
                                @include('partials/blocks/tender/procuring-entity')
    
                                {{--Обгрунтування застосування переговорної процедури--}}
                                @include('partials/blocks/tender/negotiation')
                                
                                {{--Інформація про процедуру--}}
                                @include('partials/blocks/tender/dates')
    
                                {{--Інформація про предмет закупівлі--}}
                                @include('partials/blocks/tender/info')
    
                                <h2>Документація</h2>
    
                                {{--Критерії вибору переможця--}}
                                @include('partials/blocks/tender/criteria')
    
                                {{--Тендерна документація--}}
                                @include('partials/blocks/tender/documentation')
    
    
                                @if (!empty($item->__complaints_claims) ||!empty($item->__questions))
                                    <h2>Роз’яснення до процедури</h2>
    
                                    {{--Запитання до процедури--}}
                                    @include('partials/blocks/tender/questions')
        
                                    {{--Вимоги про усунення порушення--}}
                                    @include('partials/blocks/tender/claims')
    
                                @endif
    
                                {{--Скарги до процедури--}}
                                @include('partials/blocks/tender/complaints', ['title'=>'Скарги до процедури'])
    
                                @if (!$item->__isMultiLot)
    
                                    {{--Протокол розгляду--}}
                                    @include('partials/blocks/tender/qualifications')
    
                                    {{--Реєстр пропозицій--}}
                                    @include('partials/blocks/tender/bids')
    
                                    {{--Протокол розкриття--}}
                                    @include('partials/blocks/tender/awards')
                                    
                                    {{--Повідомлення про намір укласти договір--}}                
                                    @include('partials/blocks/tender/active-awards')                
    
                                    {{--Укладений договір--}}
                                    @include('partials/blocks/tender/contract')
                    
                                    {{--Зміни до договору--}}
                                    @include('partials/blocks/tender/contract-changes')
                
                                    {{--Виконання договору--}}
                                    @include('partials/blocks/tender/contract-ongoing')
                
                                    {{--Інформація про скасування--}}
                                    @include('partials/blocks/tender/cancelled', [
                                        'tenderPeriod'=>!empty($item->tenderPeriod) ? $item->tenderPeriod : false,
                                        'qualificationPeriod'=>!empty($item->qualificationPeriod) ? $item->qualificationPeriod : false
                                    ])
    
                                @endif
                            </div>
                        </div>
                    </div>
    
                    @if($item->__isMultiLot)
                        <h2>Лоти</h2>
                        <div class="bs-example bs-example-tabs lots-tabs wide-table" data-js="lot_tabs" data-tab-class="tab-lot-content">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach($item->lots as $k=>$lot)
                                    <li role="presentation" class="{{$k==0?'active':''}}" style="font-size:80%">
                                        <a href="" role="tab" data-toggle="tab" aria-expanded="{{$k==0?'true':'false'}}">{{ !empty($lot->lotNumber) ? $lot->lotNumber : str_limit((!empty($lot->title) ? $lot->title : 'Лот '.($k+1)), 20) }}{{--Лот {{$k+1}}--}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="lots-container">
                            @foreach($item->lots as $k=>$lot)
                                <div class="tab-content tab-lot-content{{$k==0?' active':''}}">
                                    {{--Опис--}}
                                    @include('partials/blocks/lots/info', [
                                        'item'=>$lot,
                                        'tender'=>$item
                                    ])

                                    @if(in_array($lot->status, ['complete', 'unsuccessful', 'cancelled']) && $item->procurementMethod=='open' && in_array($item->procurementMethodType, ['aboveThresholdUA', 'aboveThresholdEU', 'aboveThresholdUA.defense']))
                                        <div style="margin-top:-20px;margin-bottom:40px">Друкувати звіт про результати проведення процедури <a href="{{href('tender/'.$item->tenderID.'/print/report/pdf/'.$lot->id)}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/report/html/'.$lot->id)}}" target="_blank">HTML</a></div>
                                    @endif
                                    @if(in_array($lot->status, ['complete', 'cancelled']) && $item->procurementMethod=='limited' && in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                                        <div style="margin-top:-20px;margin-bottom:40px">Друкувати звіт про результати проведення процедури <a href="{{href('tender/'.$item->tenderID.'/print/report/pdf/'.$lot->id)}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/report/html/'.$lot->id)}}" target="_blank">HTML</a></div>
                                    @endif
    
                                    {{--Позиції--}}
                                    @include('partials/blocks/lots/items', [
                                        'item'=>$lot
                                    ])
    
                                    {{--<h2>Документація</h2>--}}
    
                                    {{--Критерії вибору переможця--}}
                                    @include('partials/blocks/lots/criteria', [
                                        'item'=>$lot
                                    ])
    
                                    {{--Документація--}}
                                    @include('partials/blocks/tender/documentation',[
                                        'item'=>$lot,
                                        'lot_id'=>$lot->id
                                    ])
    
                                    {{--Запитання до лоту--}}
                                    @include('partials/blocks/tender/questions', [
                                        'item'=>$lot
                                    ])
                                    
                                    {{--Вимоги про усунення порушення до лоту--}}
                                    @include('partials/blocks/tender/claims', [
                                        'item'=>$lot
                                    ])
    
                                    {{--Скарги до лоту--}}
                                    @include('partials/blocks/tender/complaints', [
                                        'item'=>$lot,
                                        'title'=>'Скарги до лоту'
                                    ])
    
                                    {{--Протокол розгляду--}}
                                    @include('partials/blocks/tender/qualifications', [
                                        'item'=>$lot
                                    ])

                                    {{--Реєстр пропозицій--}}
                                    @include('partials/blocks/tender/bids', [
                                        'item'=>$lot
                                    ])
                                    
                                    {{--Протокол розкриття--}}
                                    @include('partials/blocks/tender/awards', [
                                        'item'=>$lot
                                    ])
    
                                    {{--Повідомлення про намір укласти договір--}}                
                                    @include('partials/blocks/tender/active-awards', [
                                        'item'=>$lot
                                    ])
    
                                    {{--Інформація про скасування--}}
                                    @include('partials/blocks/tender/cancelled', [
                                        'item'=>$lot,
                                        'tenderPeriod'=>!empty($item->tenderPeriod) ? $item->tenderPeriod : false,
                                        'qualificationPeriod'=>!empty($item->qualificationPeriod) ? $item->qualificationPeriod : false
                                    ])
                                    
                                    {{--Укладений договір--}}
                                    @include('partials/blocks/tender/contract', [
                                        'item'=>$lot,
                                        'lotID'=>$lot->id,
                                    ])
                    
                                    {{--Зміни до договору--}}
                                    @include('partials/blocks/tender/contract-changes', [
                                        'item'=>$lot,
                                        'lotID'=>$lot->id,
                                    ])
    
                                    {{--Виконання договору--}}
                                    @include('partials/blocks/tender/contract-ongoing', [
                                        'item'=>$lot,
                                        'lotID'=>$lot->id,
                                    ])
                                    
                                </div>
                            @endforeach
                        </div>
                        <?php
                            $lotted=true;
                        ?>
                    @endif

                    @if(!empty($lotted))
                        {{--Інформація про скасування--}}
                        @include('partials/blocks/tender/cancelled', [
                            'item'=>$item,
                            'tenderPeriod'=>!empty($item->tenderPeriod) ? $item->tenderPeriod : false,
                            'qualificationPeriod'=>!empty($item->qualificationPeriod) ? $item->qualificationPeriod : false
                        ])
                    @endif
    
                    {{--Подати пропозицію--}}
                    @include('partials/blocks/tender/apply')
    
                </div>
            </div>
        </div>
    @elseif ($error)
        <div style="padding:20px 20px 40px 10px;text-align:center">
            {!!$error!!}
        </div>
    @else
        <div style="padding:20px 20px 40px 10px;text-align:center">
            {{trans('tender.tender_not_found')}}
        </div>
    @endif

@endsection