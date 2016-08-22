    <div class="tender--head gray-bg">
        <div class="container">
            <div class="tender--head--title col-sm-9">{{!empty($item->title) ? $item->title : trans('facebook.tender_no_name')}}</div>

            <div class="col-md-3 col-sm-3 tender--description--cost--wr">
                @if (!empty($item->value))
                    <div class="gray-bg padding margin-bottom tender--description--cost">
                        {{trans('tender.wait_sum')}}
                        <div class="green tender--description--cost--number">
                            <strong>{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}} <span class="small">{{$item->value->currency}}</span></strong>
                        </div>
                    </div>
                @endif
            </div>
                
            <div class="row">
                <div class="col-sm-9">
                    <div class="tender--head--inf">
                        {{$item->tenderID}} ● {{$item->id}}
                    </div>
                    <div class="tender--head--inf margin-bottom">
                        {{$item->__procedure_name}}
                        @if(!empty($dataStatus[$item->status]))
                            @if(($item->__isOpenedQuestions || $item->__isOpenedClaims) && $item->procurementMethodType!='belowTheshold' && $item->status=='active.tendering' && !empty($item->tenderPeriod) && strtotime($item->tenderPeriod->endDate)<time())
                                &nbsp;&nbsp; <span class="marked yellow">Заблоковано</span>
                            @else
                                &nbsp;&nbsp; <span class="marked">{{$dataStatus[$item->status]}}</span>
                            @endif
                        @endif
                        @if($item->__icon=='pen')
                            &nbsp;&nbsp; <a href="https://ips.vdz.ua/ua/purchase_details.htm?id={{$item->id}}" target="_blank">{{trans('tender.pen_info')}}</a>
                        @endif
                        @if($item->__isOpenedQuestions)
                            &nbsp;&nbsp; Наявні запитання/вимоги без відповіді
                        @endif
                        @if($item->__isOpenedClaims)
                            &nbsp;&nbsp; Наявні {{$item->procurementMethodType != 'belowTheshold' ? 'скарги' : 'звернення'}} без рішення
                        @endif
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
                
                <div class="tender_menu_fixed" data-js="tender_menu_fixed">
                    <div class="col-sm-3 tender--menu">
                        @if($back)
                            <a href="{{$back}}" class="back-tender"><i class="sprite-arrow-left"></i> {{trans('tender.back')}}</a>
                        @endif
                        <div class="clearfix"></div>
                        @if($item->__is_apply)
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
                        <ul class="nav nav-list">{{--last--}}
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
