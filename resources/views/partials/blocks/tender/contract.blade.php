@if(!empty($item->__documents))
    <div class="container wide-table">
        <div class="margin-bottom-xl">
            <h3 class="href-left">{{trans('tender.contract_title')}}</h3>
            @if ($item->__button_007)
                <a class="href-right" href="http://www.007.org.ua/search#{{('edrpou='.$item->__button_007->edrpou.'&date_from='.$item->__button_007->date_from.'&trans_filter={"partner":"'.$item->__button_007->partner.'","type":["outgoing"]}&find=true')}}" target="_blank">Перевірити оплати</a>
            @endif
            <table class="table table-striped margin-bottom prev">
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