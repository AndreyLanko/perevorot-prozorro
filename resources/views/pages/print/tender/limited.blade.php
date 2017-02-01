@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ПОВІДОМЛЕННЯ</h2>
        <div>про намір укласти договір<br>(під час застосування переговорної процедури)<br>{{$item->tenderID}}</div>
    </center>

    <br><br>
    <?php
        $n=1;
    ?>
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
            <td>{{$n++}}. Код згідно з ЄДРПОУ замовника:</td>
            @if (!empty($item->procuringEntity->identifier->id))
                <td><strong>{{$item->procuringEntity->identifier->id}}</strong></td>
            @endif
        </tr>
        <tr>
            <td>{{$n++}}. Місцезнаходження замовника:</td>
            @if (!empty($item->procuringEntity->address))
                <td><strong>{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ' : ''}}{{!empty($item->procuringEntity->address->countryName) ? $item->procuringEntity->address->countryName.', ' : '' }}{{!empty($item->procuringEntity->address->region) ? trim(str_replace([substr(trans('tender.region'), 0, -2), 'область'], ['', ''], $item->procuringEntity->address->region)).trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</strong></td>
            @endif
        </tr>
    </table>

    @if(empty($item->lots))
        @include('partials/print/limited/lot', [
            'lots'=>[$item],
            '__item'=>$item,
            'lot_id'=>$lot_id
        ])
    @else
        @include('partials/print/limited/lot', [
            'lots'=>$item->lots,
            '__item'=>$item,
            'lot_id'=>$lot_id
        ])
    @endif
@endsection