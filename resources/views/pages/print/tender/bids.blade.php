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
        <?php
            usort($item->bids, function ($a, $b)
            {
                $datea = new \DateTime($a->date);
                $dateb = new \DateTime($b->date);
    
                return $datea>$dateb;
            });
        ?>
        @foreach($item->bids as $bid)
            <tr valign="top">
                <td>
                    @if(!empty($bid->tenderers[0]->identifier->legalName))
                        {{$bid->tenderers[0]->identifier->legalName}}
                    @else
                        {{$bid->tenderers[0]->identifier->name}}
                    @endif
                </td>
                <td>
                    {{$bid->tenderers[0]->identifier->id}}
                </td>
                <td>
                    {{date('d.m.Y H:i', strtotime($bid->date))}}
                </td>
            </tr>
        @endforeach
    </table>
@endsection