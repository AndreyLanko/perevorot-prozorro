@if (!empty($item->__complaints_claims))
    <div class="container margin-bottom-xl">
        <div class="col-sm-9">
            <h3>{{trans('tender.claims_title')}}</h3>
        
            <div class="row questions">
                <div class="description-wr questions-block">
                    @foreach($item->__complaints_claims as $k=>$complaint)
                        <div class="questions-row{{$k>1?' none':' visible'}}">
                            <div><strong>{{$complaint->title}}</strong></div>
                            <div>Статус: <div class="marked">{{trans('tender.claim_statuses.'.$complaint->status)}}</div></div>
                            @if(!empty($complaint->dateSubmitted))
                                <div class="grey-light size12 question-date">Дата подання: {{date('d.m.Y H:i', strtotime($complaint->dateSubmitted))}}</div>
                            @endif
                            @if (!empty($complaint->description))
                                <div class="description-wr margin-bottom{{mb_strlen($complaint->description)>350?' croped':' open'}}">
                                    <div class="description">
                                        {!!nl2br($complaint->description)!!}
                                    </div>
                                    @if (mb_strlen($complaint->description)>350)
                                        <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                            <span>{{trans('interface.expand')}}</span>
                                            <span>{{trans('interface.collapse')}}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                            @if(!empty($complaint->documents))
                                <a href="" class="document-link" data-id="{{$complaint->id}}-complaint">{{trans('tender.bids_documents')}}</a>
                                <br><br>
                            @endif
                            <div>
                                <div><strong>Рішення замовника</strong></div>
                                @if(empty($complaint->resolutionType))
                                    <div>Очікується</div>
                                @else
                                    <div>{{$complaint->resolutionType}}</div>
                                    @if (!empty($complaint->tendererAction))
                                        <div class="description-wr margin-bottom{{mb_strlen($complaint->tendererAction)>350?' croped':' open'}}">
                                            <div class="description">
                                                {!!nl2br($complaint->tendererAction)!!}
                                            </div>
                                            @if (mb_strlen($complaint->tendererAction)>350)
                                                <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                                    <span>{{trans('interface.expand')}}</span>
                                                    <span>{{trans('interface.collapse')}}</span>
                                                </a>
                                            @endif
                                        </div>                                    
                                    @elseif ($complaint->resolution)
                                        <div>{{$complaint->resolution}}<div>
                                    @endif
                                    <div class="grey-light size12 question-date">{{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if (sizeof($item->__complaints_claims)>2)
                        <a class="question--open"><i class="sprite-arrow-down"></i>
                            <span class="question-up">{{trans('tender.expand_claims')}}: {{sizeof($item->__complaints_claims)}}</span>
                            <span class="question-down">{{trans('tender.collapse_claims')}}</span>
                        </a>
                    @endif                                                
                </div>
                {{--trans('tender.no_complaints')--}}
            </div>
            @if(!empty($item->__complaints_claims))
                <div class="overlay overlay-documents">
                    <div class="overlay-close overlay-close-layout"></div>
                    <div class="overlay-box">
                        @foreach($item->__complaints_claims as $complaint)
                            @if(!empty($complaint->documents))
                                <div class="tender--offers documents" data-id="{{$complaint->id}}-complaint">
                                    <h4 class="overlay-title">
                                        {{trans('tender.bids_documents')}}
                                    </h4>
                                    @foreach($complaint->documents as $document)
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
            @endif
        </div>
    </div>
@endif