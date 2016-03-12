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
@endsection

@section('content')

@if ($item && !$error)

    <div class="tender" data-js="tender">

        @include('partials/blocks/tender/header')

        <div class="tender--description">
            <div class="container">
                <div class="border-bottom margin-bottom-xl">
                    <div class="row">
                        <div class="col-sm-9">

                            {{--Загальна інформація про процедуру--}}
                            @include('partials/blocks/tender/info')

                            <div class="row row-details col-sm-9">

                                {{--Документація--}}
                                @include('partials/blocks/tender/documentation')

                                {{--Дати--}}
                                @include('partials/blocks/tender/dates')

                                {{--Інформація про закупівлю--}}
                                @include('partials/blocks/tender/auction-info')

                            </div>

                            {{--Запитання до процедури--}}
                            @include('partials/blocks/tender/questions')

                            {{--Вимоги про усунення порушення до процедури--}}
                            @include('partials/blocks/tender/claims')

                            {{--Скарги до процедури--}}
                            @include('partials/blocks/tender/complaints')

                            {{--Замовник--}}
                            @include('partials/blocks/tender/procuring-entity')
                            
                            {{--Позиції--}}
                            @include('partials/blocks/tender/items')
                            
                            {{--Критерії вибору переможця--}}
                            {{--@include('partials/blocks/tender/criteria')--}}
                        </div>
                    </div>
                </div>
                
                {{--Протокол розкриття--}}

                {{--Реєстр пропозицій--}}
                @include('partials/blocks/tender/bids')
                
                {{--Повідомлення про намір укласти договір--}}                
                @include('partials/blocks/tender/active-awards')                
                
                {{--Протокол розгляду--}}
                @include('partials/blocks/tender/awards')

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