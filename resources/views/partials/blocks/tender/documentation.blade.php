<div class="container">
    <div class="col-sm-9 tender--customer--inner margin-bottom margin-bottom-more">
        <h3>Тендерна документація</h3>
    
        <div class="row">
            @if (!empty($item->__tender_documents))
                <table class="tender--customer">
                    <tbody>
                        @foreach ($item->__tender_documents as $k=>$document)
                            <tr>
                                <td class="col-sm-2" style="padding-left:0px;">{{!empty($document->dateModified) ? date('d.m.Y', strtotime($document->dateModified)) : trans('tender.no_date')}}</td>
                                <td class="col-sm-6"><a href="{{$document->url}}" target="_blank" class="word-break">{{$document->title}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="tender--customer padding-td">{{trans('tender.no_documents')}}</div>
            @endif
        </div>
    </div>
</div>