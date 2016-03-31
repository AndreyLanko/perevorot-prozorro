@extends('layouts/app')

@section('head')
    @if ($item && !$error)
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{trans('facebook.site_name')}}">
        <meta property="og:title" content="{{htmlentities($item->procuringEntity->name, ENT_QUOTES)}}">
        <meta property="og:url" content="{{Request::root()}}/{{Request::path()}}">
        <meta property="og:image" content="{{Request::root()}}/assets/images/social/fb.png">
        <meta property="og:description" content="{{!empty($item->title) ? htmlentities($item->title, ENT_QUOTES) : trans('facebook.tender_no_name')}}">
    @endif
@endsection

@section('html_header')
    @if (!empty($html))
        {!!$html['header']!!}
    @endif
@endsection

@section('html_footer')
    @if (!empty($html))
        {!!$html['footer']!!}
    @endif
    <script src="https://cdn.rawgit.com/openprocurement-crypto/common/v.0.0.7/js/index.js"></script>
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

                            @if(!empty($item->__open_name))
                                <h2>{{$item->__open_name}}</h2>
                            @endif
                            <div style="margin-top:-30px;margin-bottom:40px"><a href="{{href('tender/'.$item->tenderID.'/print/welcome')}}" target="_blank">Друкувати форму</a></div>

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

                            {{--Документація--}}
                            @include('partials/blocks/tender/documentation')


                            @if (!empty($item->__complaints_claims) ||!empty($item->__questions))
                                <h2>Роз’яснення до процедури</h2>

                                {{--Запитання до процедури--}}
                                @include('partials/blocks/tender/questions')
    
                                {{--Вимоги про усунення порушення--}}
                                @include('partials/blocks/tender/claims')

                            @endif

                            @if (!$item->__isMultiLot)

                                {{--Скарги до процедури--}}
                                @include('partials/blocks/tender/complaints', ['title'=>'Скарги до процедури'])
    
                                {{--Протокол розгляду--}}
                                @include('partials/blocks/tender/qualifications')

                                {{--Реєстр пропозицій--}}
                                @include('partials/blocks/tender/bids')

                                {{--Протокол розкриття--}}
                                @include('partials/blocks/tender/awards')
                                
                                {{--Повідомлення про намір укласти договір--}}                
                                @include('partials/blocks/tender/active-awards')                
                            @endif
                        </div>
                    </div>
                </div>

                @if($item->__isMultiLot)
                    <div class="bs-example bs-example-tabs lots-tabs wide-table" data-js="lot_tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($item->lots as $k=>$lot)
                                <li role="presentation" class="{{$k==0?'active':''}}">
                                    <a href="" role="tab" data-toggle="tab" aria-expanded="{{$k==0?'true':'false'}}">Лот {{$k+1}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="lots-container">
                        @foreach($item->lots as $k=>$lot)
                            <div class="tab-content{{$k==0?' active':''}}">
                                {{--Опис--}}
                                @include('partials/blocks/lots/info', [
                                    'item'=>$lot,
                                    'tender'=>$item
                                ])

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
                                    'item'=>$lot
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
                                {{--@include('partials/blocks/tender/active-awards')--}}
                            </div>
                        @endforeach
                    </div>
                @endif

                {{--Укладений договір--}}
                @include('partials/blocks/tender/contract')

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