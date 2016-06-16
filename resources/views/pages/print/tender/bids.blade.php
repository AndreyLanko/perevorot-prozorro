@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ФОРМА РЕЄСТРУ</h2>
        <div>отриманих тендерних пропозицій</div>
    </center>

    <br><br>
    <?php $n=1; ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr>
            <td width="302">{{$n++}}. Номер процедури закупівлі в електронній системі:</td>
            <td><strong>{{$item->tenderID}}</strong></td>
        </tr>
    </table>
    <br>
    <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
        <tr valign="top">
            <td>{{$n++}}. Найменування учасника</td>
            <td>{{$n++}}. Код згідно з ЄДРПОУ учасника</td>
            <td>{{$n++}}. Дата та час подання тендерних пропозицій</td>
        </tr>
        @if(empty($item->lots))
            @include('partials/print/bids/bids', [
                'lot'=>$item,
                'lot_id'=>$lot_id,
                '__item'=>$item
            ])
        @else
            @include('partials/print/bids/bids', [
                'lot'=>array_first($item->lots, function($key, $lot) use ($lot_id){
                    return $lot->id==$lot_id;
                }),
                '__item'=>$item,
                'lot_id'=>$lot_id,
                'n'=>$n
            ])
        @endif
    </table>
@endsection