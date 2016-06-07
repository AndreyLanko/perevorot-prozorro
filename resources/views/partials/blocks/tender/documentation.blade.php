<div class="container documents-tabs">
    <div class="col-sm-9 tender--customer--inner margin-bottom margin-bottom-more">
        <h3>Тендерна документація</h3>

        @if (!empty($item->__tender_documents))
            @if(!empty($item->__tender_documents_stroked))
                <div class="bs-example bs-example-tabs lots-tabs wide-table" data-js="lot_tabs" data-tab-class="tab-document-content{{!empty($lot_id)?'-lot-'.$lot_id:''}}">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach(['Остання редакція', 'Історія змін'] as $k=>$group)
                            <li role="presentation" class="{{$k==0?'active':''}}">
                                <a href="" role="tab" data-toggle="tab" aria-expanded="{{$k==0?'true':'false'}}">{{$group}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
    
                <div class="tab-content tab-document-content{{!empty($lot_id)?'-lot-'.$lot_id:''}} active">
            @endif
    
            <table class="tender--customer">
                <tbody>
                    @foreach ($item->__tender_documents as $k=>$document)
                        @if(empty($document->stroked))
                            <tr>
                                <td class="col-sm-2 date">{{!empty($document->dateModified) ? date('d.m.Y H:i', strtotime($document->dateModified)) : trans('tender.no_date')}}</td>
                                <td class="col-sm-6"><a href="{{$document->url}}" target="_blank" class="word-break">{{$document->title=='sign.p7s'?'Електронний цифровий підпис':$document->title}}</a></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
    
            @if(!empty($item->__tender_documents_stroked))
                </div>
                <div class="tab-content tab-document-content{{!empty($lot_id)?'-lot-'.$lot_id:''}}">
                    <table class="tender--customer">
                        <tbody>
                            @foreach ($item->__tender_documents as $k=>$document)
                                @if(empty($document->stroked))
                                    <tr>
                                        <td class="col-sm-2 date">{{!empty($document->dateModified) ? date('d.m.Y H:i', strtotime($document->dateModified)) : trans('tender.no_date')}}</td>
                                        <td class="col-sm-6">
                                            <a href="{{$document->url}}" target="_blank" class="word-break{{!empty($document->stroked) ? ' stroked': ''}}">{{$document->title=='sign.p7s'?'Електронний цифровий підпис':$document->title}}</a>
                                            <table class="tender--customer margin-bottom">
                                                <tbody>
                                                    @foreach ($item->__tender_documents as $c=>$d)
                                                        @if($d->id==$document->id && !empty($d->stroked))
                                                            <tr>
                                                                <td class="col-sm-4 stroked-date">
                                                                    {{!empty($d->dateModified) ? date('d.m.Y H:i', strtotime($d->dateModified)) : trans('tender.no_date')}}
                                                                </td>
                                                                <td>
                                                                    <a href="{{$d->url}}" target="_blank" class="word-break stroked">{{$d->title=='sign.p7s'?'Електронний цифровий підпис':$d->title}}</a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @else
            <div class="tender--customer padding-td">{{trans('tender.no_documents')}}</div>
        @endif
    </div>
</div>