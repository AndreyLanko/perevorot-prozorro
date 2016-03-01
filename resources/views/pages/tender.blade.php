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
    {{--dump($item)--}}
    <div class="tender" data-js="tender">
        <div class="tender--head gray-bg">
            <div class="container">
                <div class="tender--head--title col-sm-9">{{!empty($item->title) ? $item->title : trans('facebook.tender_no_name')}}</div>
    
                {{--
                <div class="breadcrumb_custom clearfix">
                    <a href="#" class="disable"><strong>Створена:</strong> 27.07 (сб)</a>
                    <a href="#" class="active"><strong>Уточнення:</strong> до 29.06 (пн)</a>
                    <a href="#"><strong>Пропозиції:</strong> до 6.07 (пт)</a>
                    <a href="#"><strong>Аукціон:</strong> 12.07 (пт)</a>
                    <a href="#"><strong>Кваліфікаця:</strong> з 15.07 (пн)</a>
                </div>
                --}}

                <div class="col-md-3 col-sm-3 tender--description--cost--wr">
                    @if (!empty($item->value))
                        <div class="gray-bg padding margin-bottom tender--description--cost">
                            {{trans('tender.wait_sum')}}
                            <div class="green tender--description--cost--number">
                                <strong>{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></strong>
                            </div>
                        </div>
                    @endif
                </div>
                    
                <div class="row">
                    <div class="col-sm-9">
                        @if (!empty($item->procuringEntity->name))
                            <div class="tender--head--company">{{$item->procuringEntity->name}}</div>
                        @endif
                        <div class="tender--head--inf">{{$item->__icon=='pen'?trans('tender.pen'):trans('tender.online')}}   @if(!empty($dataStatus[$item->status]))<span class="marked">{{$dataStatus[$item->status]}}</span>@endif  
                        @if($item->__icon=='pen')
                            &nbsp;&nbsp;<a href="https://ips.vdz.ua/ua/purchase_details.htm?id={{$item->id}}" target="_blank">{{trans('tender.pen_info')}}</a>
                        @endif
                             @if (!empty($item->procuringEntity->address->locality)){{$item->procuringEntity->address->locality}}@endif</div>
                    </div>
                    
                    <div class="tender_menu_fixed" data-js="tender_menu_fixed">
                        <div class="col-sm-3 tender--menu">
                            @if($back)
                                <a href="{{$back}}" class="back-tender"><i class="sprite-arrow-left"></i> {{trans('tender.back')}}</a>
                            @endif
                            <div class="clearfix"></div>
                            @if($item->is_active_proposal)
                                <a href="" class="blue-btn">{{trans('tender.apply')}}</a>
                            @endif
                            {{--
                            <ul class="nav nav-list">
                                <li>
                                    <a href="#"><i class="sprite-star"></i> Зберегти у кабінеті</a>
                                </li>
                                <li>
                                    <a href="#"><i class="sprite-close-blue"></i> Не цікаво(не відображати)</a>
                                </li>
                                <li>
                                    <a href="#"><i class="sprite-warning"></i> Поскаржитись</a>
                                </li>
                            </ul>
                            --}}
                            <ul class="nav nav-list last">
                                {{--
                                <li>
                                    <a href="#"><i class="sprite-print"></i> Роздрукувати</a>
                                </li>
                                <li>
                                    <a href="#"><i class="sprite-download"></i> Зберегти як PDF</a>
                                </li>
                                --}}
                                @if ($item->status=='active.enquiries')
                                    {{trans('tender.auction_plan')}} {{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}}
                                @elseif(in_array($item->status, ['active.tendering', 'active.auction', 'active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']) && !empty($item->auctionUrl))
                                    <li>
                                        <a href="{{$item->auctionUrl}}" target="_blank"><i class="sprite-hammer"></i> {{trans('tender.goto_auction')}}</a>
                                        @if(in_array($item->status, ['active.tendering', 'active.auction']))
                                            <p class="tender-date">{{trans('tender.auction_planned')}} {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</p>
                                        @elseif(in_array($item->status, ['active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']) && !empty($item->auctionPeriod->endDate))
                                            <p class="tender-date">{{trans('tender.auction_finished')}} {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</p>
                                        @elseif(in_array($item->status, ['active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']))
                                            <p class="tender-date">{{trans('tender.auction_not_happened')}}</p>
                                        @endif
                                    </li>
                                @endif

                                @if (!empty($item->bids))
                                    <li>
                                        <a href="" class="tender--offers--ancor"><i class="sprite-props"></i> {{trans('tender.bids')}}</a>
                                    </li>
                                @endif

                                {{--
                                <li>
                                    <a href=""><i class="sprite-share"></i> Поділитись</a>
                                </li>
                                <li>
                                    <a href=""><i class="sprite-link"></i> Скопіювати посилання</a>
                                </li>
                                --}}
                            </ul>
                            @if(!empty($item->procuringEntity->contactPoint->name) || !empty($item->procuringEntity->contactPoint->email))
                                <p><strong>{{trans('tender.contacts')}}</strong></p>
                                @if(!empty($item->procuringEntity->contactPoint->name))
                                    <p>{{$item->procuringEntity->contactPoint->name}}</p>
                                @endif
                                @if(!empty($item->procuringEntity->contactPoint->telephone))
                                    <p>{{$item->procuringEntity->contactPoint->telephone}}</p>
                                @endif
                                @if(!empty($item->procuringEntity->contactPoint->email))
                                    <small><a href="mailto:{{$item->procuringEntity->contactPoint->email}}" class="word-break">{{$item->procuringEntity->contactPoint->email}}</a></small>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tender--description">
            <div class="container">
                <div class="border-bottom margin-bottom-xl">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="margin-bottom">
                                <h3>{{trans('tender.info')}}</h3>
                                <div class="row">
                                    @if (!empty($item->description))
                                        <div class="col-md-12 description-wr croped">
                                            <div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
                                                {!!nl2br($item->description)!!}
                                            </div>
                                            @if (mb_strlen($item->description)>350)
                                                <a class="search-form--open" href="">
                                                    <i class="sprite-arrow-right"></i>
                                                    <span>{{trans('interface.expand')}}</span>
                                                    <span>{{trans('interface.collapse')}}</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if (!empty($item->items))
                                <div class="margin-bottom">
                                    <div{{empty($item->features) ? 'class="border-bottom"':''}}>
                                        <h3>{{trans('tender.items')}}</h3>
                                        @foreach($item->items as $one)
                                            <div class="row margin-bottom">
                                                <div class="col-md-4 col-md-push-8">
                                                    <div class="padding margin-bottom">
                                                        {{!empty($one->quantity)?$one->quantity.trans('tender.q'):''}}
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-md-pull-4 description-wr{{!empty($one->description) && mb_strlen($one->description)>350?' croped':' open'}}">
                                                    @if (!empty($one->description))
                                                            <div class="tender--description--text description">
                                                                {!!nl2br($one->description)!!}
                                                            </div>
                                                            @if (mb_strlen($one->description)>350)
                                                                <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                                                    <span>{{trans('interface.expand')}}</span>
                                                                    <span>{{trans('interface.collapse')}}</span>
                                                                </a>
                                                            @endif
                                                    @endif
                                                    @if (!empty($one->classification))
                                                        <div class="tender-date">{{trans('tender.cpv')}}: {{$one->classification->id}} — {{$one->classification->description}}</div>
                                                    @else
                                                        <div class="tender-date">{{trans('tender.no_cpv')}}</div>
                                                    @endif
                                                    @if(!empty($one->additionalClassifications[0]))
                                                        <div class="tender-date">{{trans('tender.dkpp')}}: {{$one->additionalClassifications[0]->id}} — {{$one->additionalClassifications[0]->description}}</div>
                                                    @else
                                                        <div class="tender-date">{{trans('tender.no_dkpp')}}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($item->__icon!='pen')
                                <div class="col-sm-9 criterii">
                                    <h3>{{trans('tender.criteria_title')}}</h3>
                                    <table class="tender--customer margin-bottom tender--customer-left">
                                        <tbody>
                                            <tr class="main-row">
                                                <td class="col-md-8 col-md-pull-4">{{trans('tender.price')}}:</td>
                                                <td class="col-md-4 col-md-push-8">{{$features_price*100}}%</td>
                                            </tr>
                                            @if(!empty($item->features))
                                                @foreach($item->features as $feature)
                                                    <tr class="main-row">
                                                        <td class="col-md-8 col-md-pull-4">{{$feature->description}}:</td>
                                                        <td class="col-md-4 col-md-push-8 1">{{$feature->max*100}}%</td>
                                                    </tr>
                                                    @foreach($feature->enum as $enum)
                                                        <tr class="add-row">
                                                            <td class="col-md-8 col-md-pull-4 grey-light">{{$enum->title}}:</td>
                                                            <td class="col-md-4 col-md-push-8 grey-light">{{$enum->value*100}}%</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <div class="row row-details col-sm-9">
                                <div class="col-sm-4 margin-bottom">
                                    <h3>{{trans('tender.documentation')}}</h3>
                                    <div class="gray-bg padding margin-bottom">
                                        @if (!empty($item->documents))
                                            <ul class="nav nav-list">
                                                @foreach ($item->documents as $k=>$document)
                                                    @if($k<=2)
                                                        <li>
                                                            {{!empty($document->dateModified) ? date('d.m.Y', strtotime($document->dateModified)) : trans('tender.no_date')}}<br>
                                                            <a href="{{$document->url}}" target="_blank" class="word-break">{{$document->title}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                {{--<li><a href="#"><i class="sprite-zip"></i> Зберегти усі документи архівом</a></li>--}}
                                            </ul>
                                            @if(sizeof($item->documents)>3)
                                                <div class="documents-all-wr"><a href="" class="documents-all">{{trans('all_documents')}} </a><span class="all-number">({{sizeof($item->documents)}})</span></div>
                                            @endif
                                            <div class="overlay overlay-documents-all">
                                                <div class="overlay-close overlay-close-layout"></div>
                                                <div class="overlay-box">
                                                    <div class="tender--offers documents" data-id="{{$item->id}}">
                                                        <h4 class="overlay-title">{{trans('tender.documentation')}}</h4>
                                                        @foreach ($item->documents as $k=>$document)
                                                            <div class="document-info">
                                                                <div class="document-date">{{!empty($document->dateModified) ? date('d.m.Y', strtotime($document->dateModified)) : trans('tender.no_date')}}</div>
                                                                <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                                            </div>
                                                        @endforeach
                                                        </div>
                                                        <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                                    </div>
                                            </div>
                                        @else
                                            <div>{{trans('tender.no_documents')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 margin-bottom ">
                                    <h3>{{trans('tender.dates')}}</h3>
                                    <div class="gray-bg padding margin-bottom">
                                        <ul class="nav nav-list">
                                            @if(!empty($item->enquiryPeriod->endDate))
                                                <li>
                                                    <strong>{{trans('tender.period1')}}:</strong><br>
                                                    {{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->enquiryPeriod->endDate))}}
                                                </li>
                                            @endif
                                            @if(!empty($item->tenderPeriod->endDate))
                                                <li>
                                                    <strong>{{trans('tender.period2')}}:</strong><br>
                                                    {{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->tenderPeriod->endDate))}}
                                                </li>
                                            @endif
                                            @if(!empty($item->auctionPeriod->startDate))
                                                <li>
                                                    <strong>{{trans('tender.period3')}}:</strong><br>
                                                    {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4 margin-bottom ">
                                    <h3>{{trans('tender.auction_info')}}</h3>
                                    <div class="gray-bg padding margin-bottom">
                                        <ul class="nav nav-list">
                                            @if (!empty($dataStatus[$item->status]))
                                                <li>
                                                    <strong>{{trans('tender.status')}}:</strong><br>{{$dataStatus[$item->status]}}
                                                </li>
                                            @endif
                                            @if (!empty($item->value->amount))
                                                <li>
                                                    <strong>{{trans('tender.budget')}}:</strong><br>{{number_format($item->value->amount, 0, '', ' ')}} {{$item->value->currency}}
                                                </li>
                                            @endif
                                            @if (!empty($item->minimalStep->amount))
                                                <li>
                                                    <strong>{{trans('tender.min_step')}}:</strong><br>{{$item->minimalStep->amount}} {{$item->minimalStep->currency}}
                                                </li>
                                            @endif
                                            <li>
                                                <strong>{{trans('tender.tender_id')}}:</strong><br>
                                                {{$item->tenderID}}
                                            </li>
                                        </ul>
                                        <div class="info-all-wr"><a href="" class="info-all">{{trans('tender.more')}}</a></span></div>
                                        <div class="overlay overlay-info-all">
                                            <div class="overlay-close overlay-close-layout"></div>
                                            <div class="overlay-box">
                                                <div class="tender--offers documents" data-id="info">
                                                    <h4 class="overlay-title">{{trans('tender.auction_info')}}</h4>
                                                    <div class="document-info">
                                                        ID
                                                        <div class="document-date"><a href="https://public.api.openprocurement.org/api/0/tenders/{{$item->id}}" target="_blank">{{$item->id}}</a></div>
                                                    </div>
                                                    @if (!empty($item->__yaml_documents))
                                                        @foreach($item->__yaml_documents as $document)
                                                            <div class="document-info">
                                                                {{trans('tender.auction_journal')}}
                                                                <div class="document-date"><a href="{{$document->url}}" target="_blank">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</a></div>
                                                            </div>                                                    
                                                        @endforeach
                                                    @endif
                                                    {{--
                                                    @if(Session('api')=='http://ocds-test.aws3.tk/search')
                                                        <div class="document-info">
                                                            JSON
                                                            <div class="document-date"><a href="http://ocds-test.aws3.tk/search?tid={{$item->tenderID}}" target="_blank">ocds-test.aws3.tk</a></div>
                                                        </div>
                                                        <div class="document-info">
                                                            ips.vdz.ua
                                                            <div class="document-date"><a href="https://ips.vdz.ua/ua/purchase_details.htm?id={{$item->id}}" target="_blank">ips.vdz.ua</a></div>
                                                        </div>
                                                    @endif
                                                    --}}
                                                </div>
                                                <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($item->__icon!='pen')
                                <div class="container">
                                    <div class="col-sm-9">
                                        <h3>{{trans('tender.questions_title')}}</h3>
                                    
                                        <div class="row questions">
                                            @if (!empty($item->questions))
                                                <div class="description-wr questions-block">
                                                    @foreach($item->questions as $k=>$question)
                                                        <div class="questions-row{{$k>1?' none':' visible'}}">
                                                            <div><strong>{{$question->title}}</strong></div>
                                                            <div class="grey-light size12 question-date">{{date('d.m.Y H:i', strtotime($question->date))}}</div>
                                                            @if (!empty($question->description))
                                                                <div class="question-one description-wr margin-bottom{{mb_strlen($question->description)>350?' croped':' open'}}">
                                                                    <div class="description">
                                                                        {{$question->description}}
                                                                    </div>
                                                                    @if (mb_strlen($question->description)>350)
                                                                        <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                                                            <span>{{trans('interface.expand')}}</span>
                                                                            <span>{{trans('interface.collapse')}}</span>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @if(!empty($question->answer))
                                                                <div class="answer"><strong>{{trans('tender.answer')}}:</strong> <i>{!!nl2br($question->answer)!!}</i></div>
                                                            @else
                                                                <div class="answer" style="font-weight: bold">{{trans('tender.no_answer')}}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    @if (sizeof($item->questions)>2)
                                                        <a class="question--open"><i class="sprite-arrow-down"></i>
                                                            <span class="question-up">{{trans('tender.expand_questions')}}: {{sizeof($item->questions)}}</span>
                                                            <span class="question-down">{{trans('tender.collapse_questions')}}</span>
                                                        </a>
                                                    @endif                                                
                                                </div>
                                            @else
                                                {{trans('tender.no_questions')}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($item->procuringEntity))
                                <div class="col-sm-9 tender--customer--inner">
                                    <h3>{{trans('tender.customer')}}</h3>
                                
                                    <div class="row">
                                        <table class="tender--customer margin-bottom">
                                            <tbody>
                                                @if (!empty($item->procuringEntity->identifier->legalName))
                                                    <tr>
                                                        <td class="col-sm-4"><strong>{{trans('tender.customer_name')}}:</strong></td>
                                                        <td class="col-sm-6">{{$item->procuringEntity->identifier->legalName}}</td>
                                                    </tr>
                                                @endif
                                                @if (!empty($item->procuringEntity->identifier->id))
                                                    <tr>
                                                        <td class="col-sm-4"><strong>{{trans('tender.customer_code')}}:</strong></td>
                                                        <td class="col-sm-6">{{$item->procuringEntity->identifier->id}}</td>
                                                    </tr>
                                                @endif
                                                @if (!empty($item->procuringEntity->contactPoint->url))
                                                    <tr>
                                                        <td class="col-sm-4"><strong>{{trans('tender.customer_website')}}:</strong></td>
                                                        <td class="col-sm-6"><a href="{{$item->procuringEntity->contactPoint->url}}" target="_blank">{{$item->procuringEntity->contactPoint->url}}</a></td>
                                                    </tr>
                                                @endif
                                                @if (!empty($item->procuringEntity->address))
                                                    <tr>
                                                        <td class="col-sm-4"><strong>{{trans('tender.customer_addr')}}:</strong></td>
                                                        <td class="col-sm-6">{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ': ''}}{{$item->procuringEntity->address->countryName}}, {{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</td>
                                                    </tr>
                                                @endif
                                                @if (!empty($item->procuringEntity->contactPoint))
                                                    <tr>
                                                        <td class="col-sm-4"><strong>{{trans('tender.customer_contact')}}:</strong></td>
                                                        <td class="col-sm-6">
                                                            @if (!empty($item->procuringEntity->contactPoint->name))
                                                                {{$item->procuringEntity->contactPoint->name}}<br>
                                                            @endif
                                                            @if (!empty($item->procuringEntity->contactPoint->telephone))
                                                                {{$item->procuringEntity->contactPoint->telephone}}<br>
                                                            @endif
                                                            @if (!empty($item->procuringEntity->contactPoint->email))
                                                                <a href="mailto:{{$item->procuringEntity->contactPoint->email}}">{{$item->procuringEntity->contactPoint->email}}</a><br>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endif    
                            </div>
                        </div>
                    </div>
                </div>
                @if($item->is_active_proposal)
                    <div class="container wide-table">
                        <div class="">
                            <div class="tender--platforms border-bottom margin-bottom-xl">
                                <h3>{{trans('tender.apply_title')}}</h3>
                                {{trans('tender.apply_info')}}
                                <div class="tender--platforms--list clearfix">
                                    @foreach($platforms as $platform)
                                        @if ($platform['tender'])
                                            <div class="item">
                                                <div class="img-wr">
                                                    <a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">
                                                        <img src="/assets/images/platforms/{{$platform['slug']}}.png" alt="{{$platform['name']}}" title="{{$platform['name']}}">
                                                    </a>
                                                </div>
                                                <div class="border-hover">
                                                    <div class="btn-wr"><a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank" class="btn">{{trans('tender.apply_go')}}</a></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                {{--<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всіх</a>--}}
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($item->bids))
                    <div class="container wide-table">
                        <div class="tender--offers margin-bottom-xl">
                            <h3>{{trans('tender.bids_title')}}</h3>
                            @if(!empty($item->auctionPeriod->endDate))
                                    <p class="table-date">{{trans('tender.bids_open_time')}}: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</p>
                            @elseif(!empty($item->tenderPeriod->endDate))
                                    <p class="table-date">{{trans('tender.bids_open_time')}}: {{date('d.m.Y H:i', strtotime($item->tenderPeriod->endDate))}}</p>
                            @endif
                            <table class="table table-striped margin-bottom small-text">
                                <thead>
                                    <tr>
                                        <th>{{trans('tender.bids_participant')}}</th>
                                        <th>{{trans('tender.bids_start_bid')}}</th>
                                        <th>{{trans('tender.bids_last_bid')}}</th>
                                        @if($features_price<1)
                                            <th>{{trans('tender.bids_coef')}}</th>
                                            <th>{{trans('tender.bids_price')}}</th>
                                        @endif
                                        <th>{{trans('tender.bids_documents')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->bids as $bid)
                                        <tr>
                                            <td>{{$bid->tenderers[0]->name}}</td>
                                            <td>
                                                    @if(!empty($item->__initial_bids[$bid->id]))
                                                            {{str_replace('.00', '', number_format($item->__initial_bids[$bid->id], 2, '.', ' '))}}
                                                @else
                                                    {{str_replace('.00', '', number_format($bid->value->amount, 2, '.', ' '))}} 
                                                @endif
                                                <div class="td-small grey-light">{{$bid->value->currency}}{{$bid->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>                                            
                                            </td>
                                                <td>
                                                {{str_replace('.00', '', number_format($bid->value->amount, 2, '.', ' '))}} 
                                                    <div class="td-small grey-light">{{$bid->value->currency}}{{$bid->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>
                                                </td>
                                            @if($features_price<1)
                                                <td>{{$bid->__featured_coef}}</td>
                                                <td class="1">{{$bid->__featured_price}}</td>
                                            @endif
                                            {{--
                                            <td>
                                                @if (!empty($item->awards))
                                                    @foreach($item->awards as $award)
                                                        @if($award->bid_id==$bid->id)
                                                            @if(!empty($award->documents))
                                                                <a href="" class="document-link" data-id="{{$bid->id}}-status">
                                                            @endif
                                                            @if($award->status=='unsuccessful')
                                                                {{trans('tender.big_status_unsuccessful')}}
                                                            @elseif($award->status=='active')
                                                                {{trans('tender.big_status_active')}}
                                                            @elseif($award->status=='pending')
                                                                {{trans('tender.big_status_pending')}}
                                                            @else
                                                                {{$award->status}}
                                                            @endif
                                                            @if(!empty($award->documents))
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            --}}
                                            <td>
                                                @if(!empty($bid->documents))
                                                    <a href="" class="document-link" data-id="{{$bid->id}}">{{trans('tender.bids_documents')}}</a>
                                                @else
                                                    {{trans('tender.no_documents')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="overlay overlay-documents">
                                <div class="overlay-close overlay-close-layout"></div>
                                <div class="overlay-box">
                                    @foreach($item->bids as $bid)
                                        <div class="tender--offers documents" data-id="{{$bid->id}}">
                                            @if(!empty($bid->__documents_before))
                                                <h4 class="overlay-title">
                                                    {{trans('tender.bids_documents_before')}}
                                                </h4>
                                                @foreach($bid->__documents_before as $document)
                                                    <div class="document-info">
                                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                                        <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(!empty($bid->__documents_after))
                                                <h4 class="overlay-title">
                                                    {{trans('tender.bids_documents_before')}}
                                                </h4>
                                                @foreach($bid->__documents_after as $document)
                                                    <div class="document-info">
                                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                                        <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (!empty($item->awards))
                    <div class="container wide-table">
                        <div class="tender--offers margin-bottom-xl">
                            <h3>{{trans('tender.awards_title')}}</h3>
                            <table class="table table-striped margin-bottom small-text">
                                <thead>
                                    <tr>
                                        <th>{{trans('tender.awards_participant')}}</th>
                                        <th>{{trans('tender.awards_result')}}</th>
                                        <th>{{trans('tender.awards_proposition')}}</th>
                                        <th>{{trans('tender.awards_published')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->awards as $award)
                                        <tr>
                                            <td>
                                                @if(!empty($award->suppliers[0]->identifier->legalName))
                                                    {{$award->suppliers[0]->identifier->legalName}}<br>
                                                @elseif(!empty($award->suppliers[0]->name))
                                                    {{$award->suppliers[0]->name}}<br>
                                                @endif
                                                #{{$award->suppliers[0]->identifier->id}}                                                
                                            </td>
                                            <td>
                                                @if(!empty($award->documents))
                                                    <a href="" class="document-link" data-id="{{$award->id}}-award">
                                                @endif
                                                @if($award->status=='unsuccessful')
                                                    {{trans('tender.big_status_unsuccessful')}}
                                                @elseif($award->status=='active')
                                                    {{trans('tender.big_status_active')}}
                                                @elseif($award->status=='pending')
                                                    {{trans('tender.big_status_pending')}}
                                                @else
                                                    {{$award->status}}
                                                @endif
                                                @if(!empty($award->documents))
                                                    </a>
                                                @endif
                                            </td>                                            
                                            <td>
                                                {{str_replace('.00', '', number_format($award->value->amount, 2, '.', ' '))}} 
                                                <div class="td-small grey-light">{{$award->value->currency}}{{$award->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>
                                            </td>
                                            <td>
                                                    {{date('d.m.Y H:i', strtotime($award->date))}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="overlay overlay-documents">
                                <div class="overlay-close overlay-close-layout"></div>
                                <div class="overlay-box">
                                    @foreach($item->awards as $award)
                                        @if (!empty($award->documents))
                                            <div class="tender--offers documents" data-id="{{$award->id}}-award">
                                            <h4 class="overlay-title">{{trans('tender.bids_documents')}}</h4>
                                                @foreach($award->documents as $document)
                                                    <div class="document-info">
                                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                                        <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                @endif
                @if(!empty($item->__active_award))
                    <div class="container wide-table">
                        <div class="tender--offers margin-bottom-xl">
                            <h3>{{trans('tender.active_awards_title')}}</h3>
                            <p class="table-date">{{trans('tender.active_awards_date')}}: {{date('d.m.Y H:i', strtotime($item->__active_award->date))}}</p>
                            <table class="table table-striped margin-bottom small-text{{$features_price<1?' long':' contract'}}">
                                <thead>
                                    <tr>
                                        <th>{{trans('tender.active_awards_participant')}}</th>
                                        <th>{{trans('tender.active_awards_proposition')}}</th>
                                        <th>{{trans('tender.active_awards_documents')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>
                                        @if(!empty($item->__active_award->suppliers[0]->identifier->legalName))
                                                {{$item->__active_award->suppliers[0]->identifier->legalName}}<br>
                                        @elseif(!empty($item->__active_award->suppliers[0]->name))
                                                {{$item->__active_award->suppliers[0]->name}}<br>
                                        @endif
                                        #{{$item->__active_award->suppliers[0]->identifier->id}}
                                    </td>
                                        <td>
                                        {{str_replace('.00', '', number_format($item->__active_award->value->amount, 2, '.', ' '))}} 
                                        <div class="td-small grey-light">{{$item->__active_award->value->currency}}{{$item->__active_award->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>
                                    </td>
                                    <td>
                                        @if(!empty($item->__active_award->documents))
                                            <a href="" class="document-link" data-id="{{$item->__active_award->id}}">{{trans('tender.bids_documents')}}</a>
                                        @else
                                            {{trans('tender.no_documents')}}
                                        @endif
                                    </td>
                                </tbody>
                            </table>
                        </div>
                        
                        @if(!empty($item->__active_award->documents))
                            <div class="overlay overlay-documents">
                                <div class="overlay-close overlay-close-layout"></div>
                                <div class="overlay-box">
                                    <div class="tender--offers documents" data-id="{{$item->__active_award->id}}">
                                        <h4 class="overlay-title">
                                            {{trans('tender.bids_documents')}}
                                        </h4>
                                        @foreach($item->__active_award->documents as $document)
                                            <div class="document-info">
                                                <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                                <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if(!empty($item->__documents))
                    <div class="container wide-table tender--platforms">
                        <div class="margin-bottom-xl">
                            <h3>{{trans('tender.contract_title')}}</h3>
                            <table class="table table-striped margin-bottom prev{{$features_price<1?'-five-col':''}}">
                                <thead>
                                    <tr>
                                        <th>{{trans('tender.contract')}}</th>
                                        <th>{{trans('tender.contract_status')}}</th>
                                        <th></th>
                                        <th>{{trans('tender.contract_published')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->__documents as $document)
                                        <tr>
                                            <td><a href="{{$document->url}}" target="_blank">{{$document->title}}</a></td>
                                            <td>{{$document->status}}</td>
                                            <td>
                                                {{--
                                                @if (!empty($document->dateSigned))
                                                    <div>{{date('d.m.Y H:i', strtotime($document->dateSigned))}}</div>
                                                @endif
                                                --}}
                                            </td>
                                            <td>
                                                <div>{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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