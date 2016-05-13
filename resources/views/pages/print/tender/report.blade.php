@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ЗВІТ</h2>
        <div>про результати проведення процедури закупівлі<br>{{$item->tenderID}}</div>
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
            <td>{{$n++}}. Код згідно з ЄДРПОУ замовника:</td>
            @if (!empty($item->procuringEntity->identifier->id))
                <td><strong>{{$item->procuringEntity->identifier->id}}</strong></td>
            @endif
        </tr>
    </table>

    @if(empty($item->lots))
        @include('partials/print/report/lot', [
            'lots'=>[$item],
            '__item'=>$item
        ])
    @else
        @include('partials/print/report/lot', [
            'lots'=>$item->lots,
            '__item'=>$item
        ])
    @endif

@endsection