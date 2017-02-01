@if (!empty($item->__bids) && $item->procurementMethod=='open')
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Реєстр пропозицій</h3>

            @if(in_array($item->status, ['active.pre-qualification.stand-still', 'active.auction', 'active.qualification', 'active.awarded', 'active', 'cancelled', 'unsuccessful', 'complete']))
                <div style="margin-top:-10px;margin-bottom:40px">Друкувати реєстр отриманих тендерних пропозицій <a href="{{href('tender/'.$item->tenderID.'/print/bids/pdf/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/bids/html/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">HTML</a></div>
            @endif
            
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
                        @if($item->__features_price<1)
                            <th>{{trans('tender.bids_coef')}}</th>
                            <th>{{trans('tender.bids_price')}}</th>
                        @endif
                        <th>{{trans('tender.bids_documents')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->__bids as $k=>$bid)
                        <tr>
                            <td>
                                @if(!empty($bid->tenderers[0]->identifier->legalName))
                                    {{$bid->tenderers[0]->identifier->legalName}}
                                @elseif(!empty($bid->tenderers[0]->name))
                                    {{$bid->tenderers[0]->name}}
                                @else
                                    Учасник
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->__initial_bids[$bid->id]))
                                    {{str_replace('.00', '', number_format($item->__initial_bids[$bid->id], 2, '.', ' '))}}
                                    <div class="td-small grey-light">@if(!empty($bid->value)) {{ $bid->value->currency }}{{$bid->value->valueAddedTaxIncluded?trans('tender.vat'):''}}@else no value @endif</div>                                            
                                @elseif(!empty($bid->value))
                                    {{str_replace('.00', '', number_format($bid->value->amount, 2, '.', ' '))}} 
                                    <div class="td-small grey-light">{{$bid->value->currency}}{{$bid->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>                                            
                                @elseif(!empty($item->bids_values[$k]->value))
                                    {{str_replace('.00', '', number_format($item->bids_values[$k]->value->amount, 2, '.', ' '))}} 
                                    <div class="td-small grey-light">{{$item->bids_values[$k]->value->currency}}{{$item->bids_values[$k]->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>                                            
                                @else
                                    — 
                                @endif
                            </td>
                            <td>
                                @if(!empty($bid->value))
                                    {{str_replace('.00', '', number_format($bid->value->amount, 2, '.', ' '))}} 
                                    <div class="td-small grey-light">{{$bid->value->currency}}{{$bid->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>
                                @elseif(!empty($item->bids_values[$k]->value))
                                    {{str_replace('.00', '', number_format($item->bids_values[$k]->value->amount, 2, '.', ' '))}} 
                                    <div class="td-small grey-light">{{$item->bids_values[$k]->value->currency}}{{$item->bids_values[$k]->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>                                            
                                @endif
                            </td>
                            @if(intval($item->__features_price)<1)
                                <td>{{!is_object($bid->__featured_coef) ? $bid->__featured_coef : '—' }}</td>
                                <td class="1">{{!is_object($bid->__featured_price) ? $bid->__featured_price : '—' }}</td>
                            @endif
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
                    @foreach($item->__bids as $bid)
                        <div class="tender--offers documents" data-id="{{$bid->id}}">
                            @if(!empty($bid->__documents_public))
                                <h4 class="overlay-title">
                                    Публічні документи
                                </h4>
                                @foreach($bid->__documents_public as $document)
                                    <div class="document-info">
                                        <div class="document-date" style="{{ !empty($document->stroked) ? "padding-left:10px;text-decoration: line-through;":"" }}">{{date('d.m.Y H:i', strtotime($document->dateModified))}}</div>
                                        <a href="{{$document->url}}" target="_blank" class="document-name" style="{{ !empty($document->stroked) ? "padding-left:10px;text-decoration: line-through;":"" }}">{{$document->title}}</a>
                                    </div>
                                @endforeach
                            @endif    
                            @if(!empty($bid->__documents_confident))
                                <h4 class="overlay-title">
                                    Конфіденційні документи
                                </h4>
                                @foreach($bid->__documents_confident as $document)
                                    <div class="document-info">
                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->dateModified))}}</div>
                                        <div>{{$document->title}}</div>
                                        <p style="font-size:80%;margin-top:10px;margin-bottom:4px;color:#AAA">Обгрунтування конфіденційності</p>
                                        @if(!empty($document->confidentialityRationale))
                                            <p style="font-size:80%;">{{$document->confidentialityRationale}}</p>
                                        @endif
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
{{--
@if (!empty($item->__eu_bids) && $item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdEU')
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Реєстр пропозицій</h3>

            <table class="table table-striped margin-bottom small-text">
                <thead>
                    <tr>
                        <th>{{trans('tender.bids_participant')}}</th>
                        <th>{{trans('tender.bids_documents')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->__eu_bids as $k=>$bid)
                        <tr>
                            <td>Учасник {{$k+1}}</td>
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
                    @foreach($item->__eu_bids as $bid)
                        <div class="tender--offers documents" data-id="{{$bid->id}}">
                            @if(!empty($bid->__documents_public))
                                <div class="margin-bottom">
                                    <h4 class="overlay-title">
                                        Публічні документи
                                    </h4>
                                    @foreach($bid->__documents_public as $document)
                                        <div class="document-info">
                                            <div class="document-date" style="{{ !empty($document->stroked) ? "padding-left:10px;text-decoration: line-through;":"" }}">{{date('d.m.Y H:i', strtotime($document->dateModified))}}</div>
                                            <a href="{{$document->url}}" target="_blank" class="document-name" style="{{ !empty($document->stroked) ? "padding-left:10px;text-decoration: line-through;":"" }}">{{$document->title}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif    
                            @if(!empty($bid->__documents_confident))
                                <div class="margin-bottom">
                                    <h4 class="overlay-title">
                                        Конфіденційні документи
                                    </h4>
                                    @foreach($bid->__documents_confident as $document)
                                        <div class="document-info">
                                            <div class="document-date">{{date('d.m.Y H:i', strtotime($document->dateModified))}}</div>
                                            <div>{{$document->title}}</div>
                                            <p style="font-size:80%;margin-top:10px;margin-bottom:4px;color:#AAA">Обгрунтування конфіденційності</p>
                                            <p style="font-size:80%;">{{$document->confidentialityRationale}}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                </div>
            </div>
        </div>
    </div>
@endif
--}}