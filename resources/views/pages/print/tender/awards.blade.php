@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ФОРМА ПРОТОКОЛУ</h2>
        <div>розкриття тендерних пропозицій</div>
    </center>

    <br><br>
    <?php $n=1; ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr>
            <td width="302">{{$n++}}. Найменування замовника:</td>
            @if (!empty($item->procuringEntity->identifier->legalName))
                <td><strong>{{$item->procuringEntity->identifier->legalName}}</strong></td>
            @elseif (!empty($item->procuringEntity->name))
                <td><strong>{{$item->procuringEntity->name}}</strong></td>
            @endif
        </tr>
        <tr>
            <td width="302">{{$n++}}. Код згідно з ЄДРПОУ замовника:</td>
            @if (!empty($item->procuringEntity->identifier->id))
                <td><strong>{{$item->procuringEntity->identifier->id}}</strong></td>
            @endif
        </tr>
        <tr>
            <td width="302">{{$n++}}. Місцезнаходження замовника:</td>
            @if (!empty($item->procuringEntity->address))
                <td><strong>{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ' : ''}}{{!empty($item->procuringEntity->address->countryName) ? $item->procuringEntity->address->countryName.', ' : '' }}{{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</strong></td>
            @endif
        </tr>
        <tr>
            <td width="302">{{$n++}}. Контактна особа замовника, уповноважена здійснювати зв’язок з учасниками:</td>
            @if(!empty($item->procuringEntity->contactPoint->name))
                <td><strong>{{$item->procuringEntity->contactPoint->name}}</strong></td>
            @endif
        </tr>
        <tr>
            <td width="302">{{$n++}}. Номер процедури закупівлі в електронній системі закупівель:</td>
            <td><strong>{{$item->tenderID}}</strong></td>
        </tr>
        <tr>
            <td width="302">{{$n++}}. Конкретна назва предмета закупівлі:</td>
            <td>
                @if(empty($item->lots))
                    @include('partials/print/awards/items', [
                        'lots'=>[$item],
                        '__item'=>$item,
                        'n'=>$n
                    ])
                @else
                    @include('partials/print/awards/items', [
                        'lots'=>$item->lots,
                        '__item'=>$item,
                        'n'=>$n
                    ])
                @endif                
            </td>
        </tr>
    </table>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr>
            <td width="302">{{$n++}}. Дата та час розкриття тендерної пропозиції:</td>
            <td><strong>{{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</strong></td>
        </tr>
    </table>
@endsection