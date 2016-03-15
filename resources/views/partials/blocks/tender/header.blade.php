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
                        <div class="tender--head--company">
                            {{$item->procuringEntity->name}}<br>                                
                        </div>
                        <div style="margin:-20px 0px 10px 0px">
                            ЄДРПОУ: {{$item->procuringEntity->identifier->id}}<br>
                            {{trans('tender.customer_addr')}}: {{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ': ''}}{{$item->procuringEntity->address->countryName}}, {{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}
                        </div>
                    @endif
                    <div class="tender--head--inf">
                        {{$item->__procedure_name}}
                        @if(!empty($dataStatus[$item->status]))
                            &nbsp;&nbsp; <span class="marked">{{$dataStatus[$item->status]}}</span>
                        @endif
                        @if($item->__icon=='pen')
                            &nbsp;&nbsp; <a href="https://ips.vdz.ua/ua/purchase_details.htm?id={{$item->id}}" target="_blank">{{trans('tender.pen_info')}}</a>
                        @endif
                        @if($item->__isOpenedQuestions)
                            &nbsp;&nbsp; Наявні запитання/вимоги без відповіді
                        @endif
                        @if($item->__isOpenedClaims)
                            &nbsp;&nbsp; Наявні скарги без рішення
                        @endif
                    </div>
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
