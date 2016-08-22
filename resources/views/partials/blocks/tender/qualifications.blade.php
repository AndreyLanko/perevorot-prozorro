@if (!empty($item->__qualifications))
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Протокол розгляду</h3>

            @if(1!=1 && in_array($item->status, ['active.pre-qualification.stand-still', 'active.auction', 'active.qualification', 'active.awarded', 'active', 'cancelled', 'unsuccessful', 'complete']))
                <div style="margin-top:-10px;margin-bottom:40px">Друкувати реєстр отриманих тендерних пропозицій <a href="{{href('tender/'.$item->tenderID.'/print/qualifications/pdf')}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/qualifications/html')}}" target="_blank">HTML</a></div>
            @endif

            <table class="table table-striped margin-bottom small-text">
                <thead>
                    <tr>
                        <th>{{trans('tender.bids_participant')}}</th>
                        <th>Документи</th>
                        <th>Рішення</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->__qualifications as $qualification)
                        <tr>
                            <td>{{$qualification->__name}}</td>
                            <td>
                                @if(!empty($qualification->__bid_documents_public) || !empty($qualification->__bid_documents_confident))
                                    <a href="" class="document-link" data-id="{{$qualification->id}}-bid">{{trans('tender.bids_documents')}}</a>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($item->status=='cancelled')
                                        @if($qualification->eligible && $qualification->qualified)
                                            Допущено до аукціону
                                        @else
                                            Відхилено
                                        @endif
                                    @else
                                        {{trans('tender.qualification_status.'.$qualification->status)}}
                                    @endif
                                </div>
                                @if(!empty($qualification->documents))
                                    <a href="" class="document-link" data-id="{{$qualification->id}}-status">{{trans('tender.bids_documents')}}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="overlay overlay-documents">
                <div class="overlay-close overlay-close-layout"></div>
                <div class="overlay-box">
                    @foreach($item->__qualifications as $qualification)
                        @if(!empty($qualification->documents))
                            <div class="tender--offers documents" data-id="{{$qualification->id}}-status">
                                @if(!empty($qualification->documents))
                                    <h4 class="overlay-title">
                                        Документи
                                    </h4>
                                    @foreach($qualification->documents as $document)
                                        <div class="document-info">
                                            <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                            <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        <div class="tender--offers documents" data-id="{{$qualification->id}}-bid">
                            @if(!empty($qualification->__bid_documents_public))
                                <h4 class="overlay-title">
                                    Публічні документи
                                </h4>
                                @foreach($qualification->__bid_documents_public as $document)
                                    <div class="document-info">
                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                        <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                                    </div>
                                @endforeach
                            @endif
                            @if(!empty($qualification->__bid_documents_confident))
                                <h4 class="overlay-title">
                                    Конфіденційні документи
                                </h4>
                                @foreach($qualification->__bid_documents_confident as $document)
                                    <div class="document-info">
                                        <div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                                        <div>{{$document->title}}</div>
                                        <p style="font-size:80%;margin-top:10px;margin-bottom:4px;color:#AAA">Обгрунтування конфіденційності</p>
                                        <p style="font-size:80%;">{{$document->confidentialityRationale}}</p>
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