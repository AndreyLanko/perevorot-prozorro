@if (!empty($item->__complaints_complaints))
    <div class="container margin-bottom-xl">
        <div class="col-sm-9">
            @if($item->procurementMethodType=='belowThreshold')
                <h2>Звернення до розгляду Комісією</h2>
            @else
                <h2>{{$title}}</h2>
            @endif
            
            <div class="row questions">
                <div class="description-wr questions-block">
                    @foreach(array_values($item->__complaints_complaints) as $k=>$complaint)
                        <div class="questions-row{{--$k>1?' none':' visible'--}}" style="margin-bottom:45px">
                            <div><strong>Номер скарги: {{!empty($complaint->complaintID)?$complaint->complaintID:$complaint->id}}</strong></div>
                            <div><strong>Статус: <div class="marked">{{$complaint->__status_name}}</div></strong></div>
                            <div class="grey-light size12 question-date">
                                @if (!empty($complaint->author->identifier->id))
                                    Скаржник: {{!empty($complaint->author->identifier->legalName) ? $complaint->author->identifier->legalName : $complaint->author->name}}, Код ЄДРПОУ:{{$complaint->author->identifier->id}}<br>
                                @endif
                                Дата подання: {{!empty($complaint->dateSubmitted) ? date('d.m.Y H:i', strtotime($complaint->dateSubmitted)) : 'відсутня'}}
                            </div>
                            
                            <div class="margin-bottom" style="margin-left:40px">
                                <div><strong>{{$complaint->title}}</strong></div>

                                @if (!empty($complaint->description))
                                    <div class="description-wr{{mb_strlen($complaint->description)>350?' croped':' open'}}">
                                        <div class="description">
                                            {!!$complaint->description!!}
                                        </div>
                                        @if (mb_strlen($complaint->description)>350)
                                            <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                                <span>{{trans('interface.expand')}}</span>
                                                <span>{{trans('interface.collapse')}}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                
                                @if(!empty($complaint->dateAnswered))
                                    <div style="margin-top:20px">
                                        @if($complaint->resolutionType=='invalid')
                                            <strong>Рішення замовника: Вимога залишена без розгляду</strong>
                                        @elseif($complaint->resolutionType=='resolved')
                                            <strong>Рішення замовника: Вимога задоволена</strong>
                                        @elseif($complaint->resolutionType=='declined')
                                            <strong>Рішення замовника: Вимога не задоволена</strong>
                                        @endif
                                    </div>
                                    <div class="grey-light size12 question-date">Дата: {{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                                    <div>{!!nl2br($complaint->resolution)!!}</div>
                                @endif
                                @if(!empty($complaint->__documents_owner))
                                    <a href="" class="document-link" style="margin-top:5px; display:block" data-id="{{$complaint->id}}-owner-complaint">{{trans('tender.bids_documents')}}</a>
                                @endif
                                @if(property_exists($complaint, 'satisfied'))
                                    <div style="margin-top:20px">
                                        @if($complaint->satisfied)
                                            <strong>Оцінка скаржника: Рішенням Замовника задоволений</strong>
                                        @else
                                            <strong>Оцінка скаржника: Рішенням Замовника не задоволений</strong>
                                            @if(!empty($complaint->dateEscalated))
                                                <div class="grey-light size12 question-date">Дата звернення до Комісії з розгляду звернень: {{date('d.m.Y H:i', strtotime($complaint->dateAnswered))}}</div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="margin-bottom">
                                <div>
                                    <strong>
                                        @if(in_array($complaint->status, ['cancelled', 'stopping']))
                                            Скарга скасована старжником
                                        @else
                                            @if($item->procurementMethodType=='belowThreshold')
                                                Рішення Комісії:
                                            @else
                                                Рішення Органу оскарження:
                                            @endif
                                            @if(in_array($complaint->status, ['pending', 'stopping']))
                                                Очікується
                                            @elseif($complaint->status=='stopped')
                                                Розгляд зупинено
                                            @elseif($complaint->status=='invalid')
                                                Залишено без розгляду
                                            @elseif($complaint->status=='satisfied')
                                                Задоволена
                                            @elseif($complaint->status=='declined')
                                                Не задоволена
                                            @endif
                                        @endif
                                    </strong>
                                </div>
                                @if(!empty($complaint->dateAccepted))
                                    <div class="grey-light size12 question-date">Прийнято до розгляду: {{date('d.m.Y H:i', strtotime($complaint->dateAccepted))}}</div>
                                @endif
                                @if(in_array($complaint->status, ['declined', 'satisfied']))
                                    @if(!empty($complaint->dateDecision))
                                        <div class="grey-light size12 question-date" style="margin-top: -10px;">Дата рішення: {{date('d.m.Y H:i', strtotime($complaint->dateDecision))}}</div>
                                    @endif
                                @endif
                                @if(in_array($complaint->status, ['cancelled', 'stopping']))
                                    @if(!empty($complaint->dateCanceled))
                                        <div class="grey-light size12 question-date">Дата: {{date('d.m.Y H:i', strtotime($complaint->dateCanceled))}}</div>
                                        Причина: {{$complaint->cancellationReason}}
                                    @endif
                                @endif
                            </div>
                            <div class="margin-bottom">
                                @if(!empty($complaint->__documents_reviewer))
                                    <div style="margin-top:10px">
                                        @foreach($complaint->__documents_reviewer as $document)
                                            <div>
                                                <a href="{{$document->url}}" target="_blank">{{$document->title}}</a>
                                                <div class="grey-light size12 question-date">Дата публікації: {{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    {{--
                    @if (sizeof($item->__complaints_complaints)>2)
                        <a class="question--open"><i class="sprite-arrow-down"></i>
                            <span class="question-up">{{trans('tender.expand_complaints')}}: {{sizeof($item->__complaints_complaints)}}</span>
                            <span class="question-down">{{trans('tender.collapse_complaints')}}</span>
                        </a>
                    @endif
                    --}}
                </div>
            </div>
            @if(!empty($item->__complaints_complaints))
                <div class="overlay overlay-documents">
                    <div class="overlay-close overlay-close-layout"></div>
                    <div class="overlay-box">
                        @foreach($item->__complaints_complaints as $complaint)
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
                            @if(!empty($complaint->__documents_reviewer))
                                <div class="tender--offers documents" data-id="{{$complaint->id}}-reviewer-complaint">
                                    <h4 class="overlay-title">
                                        Документи Органу Оскарження
                                    </h4>
                                    @foreach($complaint->__documents_reviewer as $document)
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