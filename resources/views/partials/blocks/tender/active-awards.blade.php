@if(!empty($item->__active_award) && $item->procurementMethodType!='reporting')
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>{{trans('tender.active_awards_title')}}</h3>
            @if (!empty($item->__active_award->__date))
                <p class="table-date">
                    {{trans('tender.active_awards_date')}}: {{$item->__active_award->__date}}
                </p>
            @endif

            @if(!empty($item->__print_href) && in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                <div style="margin-top:0px;margin-bottom:40px">Друкувати форму повідомлення <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/'.$item->__print_href.'/html')}}" target="_blank">HTML</a></div>
            @endif

            @if(in_array($item->procurementMethodType, ['aboveThresholdUA', 'aboveThresholdUA.defense', 'aboveThresholdEU']))
                <div style="margin-top:0px;margin-bottom:40px">Друкувати повідомлення про намір укласти договір <a href="{{href('tender/'.$item->tenderID.'/print/active-awards/pdf/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/active-awards/html/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">HTML</a></div>
            @endif

            <table class="table table-striped margin-bottom small-text{{$item->__features_price<1?' long':' contract'}}">
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