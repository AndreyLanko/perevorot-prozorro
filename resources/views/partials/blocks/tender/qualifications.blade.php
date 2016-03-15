@if (!empty($item->__qualifications) && !$item->__isMultiLot)
    <div class="container wide-table">
        <div class="tender--offers margin-bottom-xl">
            <h3>Протокол розкриття</h3>
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
                                <div>{{trans('tender.qualification_status.'.$qualification->status)}}</div>
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
                                        <p><strong>Обгрунтування конфіденційності:</strong> </p> <p>confidentialityRationale</p>
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