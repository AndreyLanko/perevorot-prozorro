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
                            <div class="tender--head--company">
                                @if(!empty($item->procuringEntity->identifier->legalName))
                                    {{$item->procuringEntity->identifier->legalName}}, 
                                @elseif(!empty($item->procuringEntity->name))
                                    {{$item->procuringEntity->name}}, 
                                @endif
                                #{{$item->procuringEntity->identifier->id}}
                            </div>        
                        </div>
                    </div>
                </div>
            </div>
            <div class="tender--description">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                <div class="margin-bottom">
                    <h3>{{trans('plan.id')}}</h3>
                    <div class="row">
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description open">
                                {{$item->planID}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="margin-bottom">
                    <h3>CPV</h3>
                    <div class="row">
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description open">
                                @if(!empty($item->classification))
                                    {{$item->classification->id}}: {{$item->classification->description}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
                @if(!empty($item->additionalClassifications))
                    <div class="margin-bottom">
                        <h3>{{trans('plan.dkpp')}}</h3>
                        <div class="row">
                            <div class="col-md-12 description-wr croped">
                                <div class="tender--description--text description open">
                                    @foreach ($item->additionalClassifications as $additionalClassification)
                                        <div>{{$additionalClassification->id}}: {{$additionalClassification->description}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="margin-bottom">
                    <h3>{{trans('plan.table.planned_date')}}</h3>
                    <div class="row">
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description open">
                                @if (!empty($item->tender->tenderPeriod))
                                    {{date('d.m.Y H:i', strtotime($item->tender->tenderPeriod->startDate))}}
                                @else
                                    {{trans('plan.no_date')}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>                                   
                <div class="margin-bottom">
                    <h3>{{trans('plan.date')}}</h3>
                    <div class="row">
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description open">
                                {{date('d.m.Y H:i', strtotime($item->dateModified))}}
                            </div>
                        </div>
                    </div>
                </div>
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