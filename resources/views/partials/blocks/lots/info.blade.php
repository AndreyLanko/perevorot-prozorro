<div class="margin-bottom">    
    <div class="row">
        @if (in_array($item->procurementMethodType, ['aboveThresholdEU', 'competitiveDialogueEU', 'aboveThresholdUA.defense']))
            @if (Lang::getLocale() == 'ua')
                <h3>Інформація про лот</h3>
                <div style="margin-top:-15px;margin-bottom:20px">Information about lots</div>
                <div class="margin-bottom-more" >
                    <div>Предмет закупівлі: {{!empty($item->title) ? $item->title : 'без назви'}}</div>
                    @if (!empty($item->description))
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
                                Опис предмету закупівлі: {!!nl2br($item->description)!!}
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

                    <div>Статус: {{trans('tender.lot_status.'.$item->status)}}</div>
                    
                    @if (!empty($item->value))
                        <div>
                            Очікувана вартість: <strong>{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}} <span class="small">{{$item->value->currency}}</span></strong>
                            @if($item->value->valueAddedTaxIncluded)
                                з ПДВ
                            @else
                                без ПДВ
                            @endif
                        </div>
                    @endif

                    @if (!empty($item->minimalStep))
                        <div>Мінімальний крок аукціону: <strong>{{str_replace('.00', '', number_format($item->minimalStep->amount, 2, '.', ' '))}} <span class="small">{{$item->minimalStep->currency}}</span></strong>
                            @if($item->minimalStep->valueAddedTaxIncluded)
                                з ПДВ
                            @else
                                без ПДВ
                            @endif
                        </div>
                    @endif

                    @if (!empty($item->guarantee) && (int) $item->guarantee->amount>0)
                        <div>Вид тендерного забезпечення: <strong>Електронна гарантія</strong></div>
                        <div>Сума тендерного забезпечення: <strong>{{str_replace('.00', '', number_format($item->guarantee->amount, 2, '.', ' '))}} {{$item->guarantee->currency}}</strong></div>
                    @else
                        <div>Вид тендерного забезпечення: <strong>Відсутній</strong></div>
                    @endif

                    <br><br>
                    <div>Title: {{!empty($item->title_en) ? $item->title_en : 'без назви'}}</div>
                    @if (!empty($item->description_en))
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description{{mb_strlen($item->description_en)>350?' croped':' open'}}">
                                Description: {!!nl2br($item->description_en)!!}
                            </div>
                            @if (mb_strlen($item->description_en)>350)
                                <a class="search-form--open" href="">
                                    <i class="sprite-arrow-right"></i>
                                    <span>{{trans('interface.expand')}}</span>
                                    <span>{{trans('interface.collapse')}}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                    <div>Current status: {{trans('tender.lot_status_en.'.$item->status)}}</div>
                    @if (!empty($item->value))
                        <div>
                            Estimated total value: <strong>{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}} <span class="small">{{$item->value->currency}}</span></strong>
                            @if($item->value->valueAddedTaxIncluded)
                                including VAT
                            @else
                                excluding VAT
                            @endif
                        </div>
                    @endif
                    @if (!empty($item->minimalStep))
                        <div>Minimal lowering step: <strong>{{str_replace('.00', '', number_format($item->minimalStep->amount, 2, '.', ' '))}} <span class="small">{{$item->minimalStep->currency}}</span></strong>
                            @if($item->minimalStep->valueAddedTaxIncluded)
                                including VAT
                            @else
                                excluding VAT
                            @endif
                        </div>
                    @endif
                    @if (!empty($item->guarantee) && (int) $item->guarantee->amount>0)
                        <div>Type of tender guarantee: <strong>Electronic guarantee</strong></div>
                        <div>Sum of tender guarantee: <strong>{{str_replace('.00', '', number_format($item->guarantee->amount, 2, '.', ' '))}} {{$item->guarantee->currency}}</strong></div>
                    @else
                        <div>Type of tender guarantee: <strong>none</strong></div>
                    @endif
                    
                </div>

                @if (!empty($item->auctionPeriod->startDate) || !empty($item->auctionPeriod->endDate) || !empty($item->auctionUrl))
                    <h3>Аукціон</h3>
                    <div class="margin-bottom-more">
                        @if (!empty($item->auctionPeriod->startDate))
                            <div>Початок: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</div>
                            <div>Auction planned date: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</div>
                        @endif
                        @if (!empty($item->auctionPeriod->endDate))
                            <div>Закінчення: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</div>
                        @endif
                        @if(!empty($item->auctionUrl))
                            <div><a href="{{$item->auctionUrl}}" target="_blank">Перейти на аукціон</a></div>
                            <div><a href="{{$item->auctionUrl}}" target="_blank">Auction link</a></div>
                        @endif
                        @if(!in_array($tender->status, ['active.enquiries', 'active.tendering', 'active.pre-qualification', 'active.pre-qualification.stand-still']) && empty($item->auctionPeriod->startDate) && empty($item->auctionPeriod->endDate))
                            <div><strong>Аукціон не проводився</strong></div>
                            <div>Auction will be planned after: {{date('d.m.Y H:i', strtotime($item->tenderPeriod->startDate))}}</div>
                        @endif
                    </div>
                @endif
            @else
                <h3>Information about lots</h3>
                <h4>Інформація про лот</h4>
                <div class="margin-bottom-more" >
                    <div>Title: {{!empty($item->title_en) ? $item->title_en : 'without name'}}</div>
                    @if (!empty($item->description_en))
                        <div class="col-md-12 description-wr croped">
                            <div class="tender--description--text description{{mb_strlen($item->description_en)>350?' croped':' open'}}">
                                Description: {!!nl2br($item->description_en)!!}
                            </div>
                            @if (mb_strlen($item->description_en)>350)
                                <a class="search-form--open" href="">
                                    <i class="sprite-arrow-right"></i>
                                    <span>{{trans('interface.expand')}}</span>
                                    <span>{{trans('interface.collapse')}}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                    <div>Current status: {{trans('tender.lot_status.'.$item->status)}}</div>
                    @if (!empty($item->value))
                        <div>
                            Estimated total value: <strong>{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}} <span class="small">{{$item->value->currency}}</span></strong>
                            @if($item->value->valueAddedTaxIncluded)
                                including VAT
                            @else
                                excluding VAT
                            @endif
                        </div>
                    @endif
                    @if (!empty($item->minimalStep))
                        <div>Minimal lowering step: <strong>{{str_replace('.00', '', number_format($item->minimalStep->amount, 2, '.', ' '))}} <span class="small">{{$item->minimalStep->currency}}</span></strong>
                            @if($item->minimalStep->valueAddedTaxIncluded)
                                including VAT
                            @else
                                excluding VAT
                            @endif
                        </div>
                    @endif
                    @if (!empty($item->guarantee) && (int) $item->guarantee->amount>0)
                        <div>Type of tender guarantee: <strong>Electronic guarantee</strong></div>
                        <div>Sum of tender guarantee: <strong>{{str_replace('.00', '', number_format($item->guarantee->amount, 2, '.', ' '))}} {{$item->guarantee->currency}}</strong></div>
                    @else
                        <div>Type of tender guarantee: <strong>Nothing</strong></div>
                    @endif
                </div>

                @if (!empty($item->auctionPeriod->startDate) || !empty($item->auctionPeriod->endDate) || !empty($item->auctionUrl))
                    <h3>Auction</h3>
                    <div class="margin-bottom-more">
                        @if (!empty($item->auctionPeriod->startDate))
                            <div>Auction planned date: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</div>
                        @endif
                        {{--@if (!empty($item->auctionPeriod->endDate))--}}
                            {{--<div>Auction link: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</div>--}}
                        {{--@endif--}}
                        @if(!empty($item->auctionUrl))
                            <div><a href="{{$item->auctionUrl}}" target="_blank">Auction link</a></div>
                        @endif
                        {{--@if(!in_array($tender->status, ['active.enquiries', 'active.tendering', 'active.pre-qualification', 'active.pre-qualification.stand-still']) && empty($item->auctionPeriod->startDate) && empty($item->auctionPeriod->endDate))--}}
                            {{--<div><strong>Аукціон не проводився</strong></div>--}}
                        {{--@endif--}}

                        @if($item->status == 'active.qualification')
                            <div>Auction will be planned after: {{date('d.m.Y H:i', strtotime($item->tenderPeriod->startDate))}}</div>
                        @endif
                    </div>
                @endif
            @endif
        @else
            <h3>Інформація про лот</h3>
            <div class="margin-bottom-more" >
                <div>Предмет закупівлі: {{!empty($item->title) ? $item->title : 'без назви'}}</div>
                @if (!empty($item->description))
                    <div class="col-md-12 description-wr croped">
                        <div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
                            Опис предмету закупівлі: {!!nl2br($item->description)!!}
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
                <div>Статус: {{trans('tender.lot_status.'.$item->status)}}</div>
                @if (!empty($item->value))
                    <div>
                        Очікувана вартість: <strong>{{str_replace('.00', '', number_format($item->value->amount, 2, '.', ' '))}} <span class="small">{{$item->value->currency}}</span></strong>
                        @if($item->value->valueAddedTaxIncluded)
                            з ПДВ
                        @else
                            без ПДВ
                        @endif
                    </div>
                @endif
                @if (!empty($item->minimalStep))
                    <div>Мінімальний крок аукціону: <strong>{{str_replace('.00', '', number_format($item->minimalStep->amount, 2, '.', ' '))}} <span class="small">{{$item->minimalStep->currency}}</span></strong>
                        @if($item->minimalStep->valueAddedTaxIncluded)
                            з ПДВ
                        @else
                            без ПДВ
                        @endif
                    </div>
                @endif
                @if (!empty($item->guarantee) && (int) $item->guarantee->amount>0)
                    <div>Вид тендерного забезпечення: <strong>Електронна гарантія</strong></div>
                    <div>Сума тендерного забезпечення: <strong>{{str_replace('.00', '', number_format($item->guarantee->amount, 2, '.', ' '))}} {{$item->guarantee->currency}}</strong></div>
                @else
                    <div>Вид тендерного забезпечення: <strong>Відсутній</strong></div>
                @endif
            </div>

            @if (!empty($item->auctionPeriod->startDate) || !empty($item->auctionPeriod->endDate) || !empty($item->auctionUrl))
                <h3>Аукціон</h3>
                <div class="margin-bottom-more">
                    @if (!empty($item->auctionPeriod->startDate))
                        <div>Початок: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</div>
                    @endif
                    @if (!empty($item->auctionPeriod->endDate))
                        <div>Закінчення: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</div>
                    @endif
                    @if(!empty($item->auctionUrl))
                        <div><a href="{{$item->auctionUrl}}" target="_blank">Перейти на аукціон</a></div>
                    @endif
                    @if(!in_array($tender->status, ['active.enquiries', 'active.tendering', 'active.pre-qualification', 'active.pre-qualification.stand-still']) && empty($item->auctionPeriod->startDate) && empty($item->auctionPeriod->endDate))
                        <div><strong>Аукціон не проводився</strong></div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
