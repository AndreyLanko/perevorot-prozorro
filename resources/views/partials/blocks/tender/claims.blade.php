<style>
    .m-10{
        margin:10px 0px 10px 0px;
    }
</style>
@if (!empty($item->__complaints_claims))
    <div class="container margin-bottom-xl">
        <div class="col-sm-9">
            <h3>{{trans('tender.claims_title')}}</h3>
        
            <div class="row questions">
                <div class="description-wr questions-block">
                    @foreach($item->__complaints_claims as $k=>$complaint)
                        <div class="questions-row{{--$k>1?' none':' visible'--}}" style="margin-bottom:45px">
                            <div class="m-10"><strong>Номер вимоги: {{!empty($complaint->complaintID)?$complaint->complaintID:$complaint->id}}</strong></div>
                            <div class="m-10"><strong>Статус: <div class="marked">{{$complaint->__status_name}}</div></strong></div>
                            <div class="m-10 grey-light size12 question-date">
                                @if (!empty($complaint->author->identifier->id))
                                    Учасник: {{!empty($complaint->author->identifier->legalName) ? $complaint->author->identifier->legalName : $complaint->author->name}}, Код ЄДРПОУ:{{$complaint->author->identifier->id}}<br>
                                @endif
                            </div>
                            
                            @if(!empty($complaint->dateSubmitted))
                                <div class="m-10 grey-light size12 question-date">Дата подання: {{date('d.m.Y H:i', strtotime($complaint->dateSubmitted))}}</div>
                            @endif
                            
                            <div class="margin-bottom margin-top"><strong>{{$complaint->title}}</strong></div>
                            
                            @if (!empty($complaint->description))
                                <div class="m-10 description-wr{{mb_strlen($complaint->description)>350?' croped':' open'}}">
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
                            
                            @if(!empty($complaint->__documents_owner))
                                <div class="m-10"><a href="" class="document-link" data-id="{{$complaint->id}}-owner-complaint">{{trans('tender.bids_documents')}}</a></div>
                            @endif
                            
                            @if(in_array($complaint->status, ['cancelled']))
                                <div class="m-10">
                                    <div><strong>Скасована</strong></div>
                                    @if(!empty($complaint->dateCanceled))
                                        <div class="grey-light size12 question-date">Дата: {{date('d.m.Y H:i', strtotime($complaint->dateCanceled))}}</div>
                                        Причина: {{$complaint->cancellationReason}}
                                    @endif
                                </div>
                            @endif
                            
                            @if(in_array($complaint->status, ['claim']))
                                <div class="m-10">
                                    <strong>Рішення Замовника: Очікується</strong>
                                </div>
                            @endif
                            
                            @if(!empty($complaint->resolutionType) || !empty($complaint->tendererActionDate))
                                <div class="m-10">
                                    @if($complaint->resolutionType=='invalid')
                                        <strong>Рішення замовника: Вимога відхилена</strong>
                                    @elseif($complaint->resolutionType=='resolved')
                                        <strong>Рішення замовника: Вимога задоволена</strong>
                                    @elseif($complaint->resolutionType=='declined')
                                        <strong>Рішення замовника: Вимога не задоволена</strong>                                
                                    @else(empty($complaint->resolutionType))
                                        <strong>Відповідь замовника: </strong>
                                    @endif
                                </div>
                            @endif
                            
                            @if (!empty($complaint->dateAnswered))
                                <div class="m-10 grey-light size12 question-date">{{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                            @endif
                            
                            @if (!empty($complaint->tendererAction))
                                <div class="m-10 description-wr margin-bottom{{mb_strlen($complaint->tendererAction)>350?' croped':' open'}}">
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
                            @endif
                            
                            @if (!empty($complaint->resolution))
                                <div class="m-10">{!!nl2br($complaint->resolution)!!}</div>
                            @endif
                            
                            @if(!empty($complaint->__documents_tender_owner))
                                <div class="m-10"><a href="" class="document-link" style="margin-top:5px; display:block" data-id="{{$complaint->id}}-tender-complaint">{{trans('tender.bids_documents')}}</a></div>
                            @endif
                            
                            @if(property_exists($complaint, 'satisfied'))
                                <div class="margin-top">
                                    @if($complaint->satisfied)
                                        <strong>Оцінка скаржником рішення Замовника: Задовільно</strong>
                                    @else
                                        <strong>Оцінка скаржником рішення Замовника: Незадовільно</strong>
                                        @if(!empty($complaint->dateEscalated))
                                            <div class="grey-light size12 question-date">Дата звернення до Комісії з розгляду звернень: {{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                            {{--
                            <div style="margin-top:20px;">
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
                                        <div>{{$complaint->resolution}}</div>
                                    @endif
                                    <div class="grey-light size12 question-date">{{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                                @endif
                            </div>
                            --}}
                        </div>
                    @endforeach
                    {{--
                    @if (sizeof($item->__complaints_claims)>2)
                        <a class="question--open"><i class="sprite-arrow-down"></i>
                            <span class="question-up">{{trans('tender.expand_claims')}}: {{sizeof($item->__complaints_claims)}}</span>
                            <span class="question-down">{{trans('tender.collapse_claims')}}</span>
                        </a>
                    @endif
                    --}}
                </div>
            </div>
            @if(!empty($item->__complaints_claims))
                <div class="overlay overlay-documents">
                    <div class="overlay-close overlay-close-layout"></div>
                    <div class="overlay-box">
                        @foreach($item->__complaints_claims as $complaint)
                            @if(!empty($complaint->__documents_owner))
                                <div class="tender--offers documents" data-id="{{$complaint->id}}-owner-complaint">
                                    <h4 class="overlay-title">
                                        Документи подані скаржником
                                    </h4>
                                    @foreach($complaint->__documents_owner as $document)
                                        <div class="document-info">
                                            <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                            <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($complaint->__documents_tender_owner))
                                <div class="tender--offers documents" data-id="{{$complaint->id}}-tender-complaint">
                                    <h4 class="overlay-title">
                                        Документи
                                    </h4>
                                    @foreach($complaint->__documents_tender_owner as $document)
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