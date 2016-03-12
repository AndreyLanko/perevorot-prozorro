@if (!empty($item->bids))
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Реєстр пропозицій</h3>

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