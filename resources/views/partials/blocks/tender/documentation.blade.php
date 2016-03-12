<div class="col-sm-4 margin-bottom">
    <h3>{{trans('tender.documentation')}}</h3>
    <div class="gray-bg padding margin-bottom">
        @if (!empty($item->__tender_documents))
            <ul class="nav nav-list">
                @foreach ($item->__tender_documents as $k=>$document)
                    @if($k<=2)
                        <li>
                            {{!empty($document->dateModified) ? date('d.m.Y', strtotime($document->dateModified)) : trans('tender.no_date')}}<br>
                            <a href="{{$document->url}}" target="_blank" class="word-break">{{$document->title}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if(sizeof($item->__tender_documents)>3)
                <div class="documents-all-wr"><a href="" class="documents-all">{{trans('all_documents')}} </a><span class="all-number">({{sizeof($item->documents)}})</span></div>
            @endif
            <div class="overlay overlay-documents-all">
                <div class="overlay-close overlay-close-layout"></div>
                <div class="overlay-box">
                    <div class="tender--offers documents" data-id="{{$item->id}}">
                        <h4 class="overlay-title">{{trans('tender.documentation')}}</h4>
                        @foreach ($item->__tender_documents as $k=>$document)
                            <div class="document-info">
                                <div class="document-date">{{!empty($document->dateModified) ? date('d.m.Y', strtotime($document->dateModified)) : trans('tender.no_date')}}</div>
                                <a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="overlay-close"><i class="sprite-close-grey"></i></div>
                </div>
            </div>
        @else
            <div>{{trans('tender.no_documents')}}</div>
        @endif
    </div>
</div>