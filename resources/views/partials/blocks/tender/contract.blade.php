@if(!empty($item->__documents))
    <div class="container wide-table tender--platforms">
        <div class="margin-bottom-xl">
            <h3>{{trans('tender.contract_title')}}</h3>
            <table class="table table-striped margin-bottom prev{{$features_price<1?'-five-col':''}}">
                <thead>
                    <tr>
                        <th>{{trans('tender.contract')}}</th>
                        <th>{{trans('tender.contract_status')}}</th>
                        <th></th>
                        <th>{{trans('tender.contract_published')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->__documents as $document)
                        <tr>
                            <td><a href="{{$document->url}}" target="_blank">{{$document->title}}</a></td>
                            <td>{{$document->status}}</td>
                            <td>
                                {{--
                                @if (!empty($document->dateSigned))
                                    <div>{{date('d.m.Y H:i', strtotime($document->dateSigned))}}</div>
                                @endif
                                --}}
                            </td>
                            <td>
                                <div>{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif