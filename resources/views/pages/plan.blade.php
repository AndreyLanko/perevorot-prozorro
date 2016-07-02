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
        <div class="tender">
            <div class="tender--head gray-bg">
                <div class="container">
                    <div class="tender--head--title col-sm-9">{{$item->budget->description}}</div>
                        <div class="col-md-3 col-sm-3 tender--description--cost--wr">
                            <div class="gray-bg padding margin-bottom tender--description--cost">
                                {{trans('plan.table.sum')}}
                                <div class="green tender--description--cost--number">
                                    <strong>{{number_format($item->budget->amount, 0, '', ' ')}} <span class="small">{{$item->budget->currency}}</span></strong>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="tender--head--inf">
                                {{$item->planID}} ● {{$item->id}}
                            </div>
                            <div class="tender--head--inf margin-bottom">
                                Опубліковано/змінено: {{date('d.m.Y H:i', strtotime($item->dateModified))}}
                            </div>
                            @if(!empty($item->__is_sign))
                                <div data-js="tender_sign_check" data-url="{{$item->__sign_url}}">
                                    Електронний цифровий підпис накладено. <a href="" class="document-link" data-id="sign-check">Перевірити</a>
                                    <div class="overlay overlay-documents">
                                        <div class="overlay-close overlay-close-layout"></div>
                                        <div class="overlay-box">
                                            <div class="documents" data-id="sign-check">
                                                <h4 class="overlay-title">Перевірка підпису</h4>
                                                <div class="loader"></div>
                                                <div id="signPlaceholder"></div>
                                            </div>
                                            <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div>Електронний цифровий підпис не накладено</div>
                            @endif                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="tender--description">
                <div class="container">
                    <h2>ФОРМА РІЧНОГО ПЛАНУ ЗАКУПІВЕЛЬ</h2>
                    @if (!empty($item->budget->year))
                        <div style="margin:-30px 0px 40px 0px">на {{$item->budget->year}} рік</div>
                    @endif
                </div>
            </div>            

            <div class="tender--description margin-bottom-xl">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <h3>Інформація про замовника</h3>
                            <div>1. Найменування замовника: @if(!empty($item->procuringEntity->identifier->legalName)){{$item->procuringEntity->identifier->legalName}}@elseif(!empty($item->procuringEntity->name)){{$item->procuringEntity->name}}@endif</div>
                            <div>2. Код згідно з ЄДРПОУ замовника: #{{$item->procuringEntity->identifier->id}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tender--description margin-bottom-xl">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <h3>Інформація про предмет закупівлі</h3>
                            <div>3. Конкретна назва предмета закупівлі: <strong>{{$item->budget->description}}</strong></div>
                            <br>
                            @if(!empty($item->__items))
                                <div class="margin-bottom">4. Коди відповідних класифікаторів предмета закупівлі:</div>
                                @foreach($item->__items as $one)
                                    <div class="margin-bottom">
                                        <div class="description-wr">
                                            <div class="tender--description--text description" style="margin-left:20px;">
                                               {{trans('interface.scheme.'.$one->scheme)}}: {{$one->id}} — {!!nl2br($one->description)!!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($item->classification)
                                    <div class="tender--description--text description" style="margin-left:20px;">
                                       {{trans('tender.cpv')}}: {{$item->classification->id}} — {!!nl2br($item->classification->description)!!}
                                    </div>
                                @endif
                            @else
                                <div class="margin-bottom">4. Коди відповідних класифікаторів предмета закупівлі: <strong>відсутні</strong></div>
                            @endif
                            <br>
                            @if(!empty($item->__items_kekv))
                                <div class="margin-bottom">5. Код згідно з КЕКВ: </div>
                                @foreach($item->__items_kekv as $one)
                                    <div class="margin-bottom">
                                        <div class="description-wr">
                                            <div class="tender--description--text description" style="margin-left:20px;">
                                                {{$one->scheme}}: {{$one->id}} — {!!nl2br($one->description)!!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="margin-bottom">5. Код згідно з КЕКВ: <strong>відсутній</strong></div>
                            @endif
                            <br>
                            <div>6. Розмір бюджетного призначення за кошторисом або очікувана вартість предмета закупівлі: <strong>{{number_format($item->budget->amount, 0, '', ' ')}} {{$item->budget->currency}}</strong></div>
                            <br>
                            <div>7. Процедура закупівлі: <strong>{{$item->tender->__procedure_name}}</strong></div>
                            <br>
                            <div>
                                8. Орієнтовний початок проведення процедури закупівлі:
                                <strong>
                                    @if ($item->__is_first_month)
                                        {{$item->__is_first_month}}
                                    @else
                                        {{date('d.m.Y', strtotime($item->tender->tenderPeriod->startDate))}}
                                    @endif
                                </strong>
                            </div>
                            <br>
                            @if(!empty($item->budget->notes))
                                <div>9. Примітки: <strong>{{$item->budget->notes}}</strong></div>
                            @endif
                        </div>
                    </div>
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