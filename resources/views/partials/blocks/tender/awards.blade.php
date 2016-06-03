@if (!empty($item->awards) && $item->procurementMethod=='open')
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Протокол розкриття</h3>

            @if (1!=1 && !empty($item->__qualifications))
                <div style="margin-top:-10px;margin-bottom:40px">Друкувати протокол розкриття тендерних пропозицій <a href="{{href('tender/'.$item->tenderID.'/print/awards/pdf/'.$item->id)}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/awards/html/'.$item->id)}}" target="_blank">HTML</a></div>
            @endif

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
                                @elseif($award->status=='cancelled')
                                    {{trans('tender.big_status_cancelled')}}
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