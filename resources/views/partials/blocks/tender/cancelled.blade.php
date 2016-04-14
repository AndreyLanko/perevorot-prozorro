@if($item->status=='cancelled' && !empty($item->__cancellations))
    <div class="row">
        <div class="container margin-bottom-xl">
            <div class="col-sm-9">
                <h3>Інформація про скасування</h3>
    
                @foreach($item->__cancellations as $cancellation)
                    <div class="row">
                        <div class="col-md-12 margin-bottom">
                            <strong>Дата скасування</strong>
                            <div>{{date('d.m.Y H:i', strtotime($cancellation->date))}}</div>
                        </div>
                        @if(!empty($cancellation->reason))
                            <div class="col-md-12 margin-bottom">
                                <strong>Причина скасування</strong>
                                <div>{{$cancellation->reason}}</div>
                            </div>
                        @endif
                        <div class="col-md-12 margin-bottom">
                            <strong>Документи</strong>
                            @if (!empty($cancellation->documents))
                                <table class="tender--customer">
                                    <tbody>
                                        @foreach($cancellation->documents as $k=>$document)
                                            <tr>
                                                <td class="col-sm-2" style="padding-left:0px;">{{!empty($document->dateModified) ? date('d.m.Y H:i', strtotime($document->dateModified)) : trans('tender.no_date')}}</td>
                                                <td class="col-sm-6"><a href="{{$document->url}}" target="_blank" class="word-break{{!empty($document->stroked) ? ' stroked': ''}}">{{$document->title}}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="tender--customer padding-td">{{trans('tender.no_documents')}}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif