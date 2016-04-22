@extends('layouts/print')

@section('pdf')
<style type="text/css" media="print">
    @page {
        size:  auto;
        margin: 0mm;
        size: landscape;
    }
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    html{
        background-color: #FFFFFF; 
        margin: 0px;
    }
    body{
        margin:1cm;
    }
</style>
    @if($main)
        <center>
            <h2>РІЧНИЙ ПЛАН ЗАКУПІВЕЛЬ</h2>
            <div>на {{ $budget->year }} рік</div>
        </center>
        <br><br>
        <?php $n=1; ?>
    
        <p>{{$n++}}. Найменування замовника: {{ !empty($procuringEntity->identifier->legalName) ? $procuringEntity->identifier->legalName : $procuringEntity->name }}</p>
        <p>{{$n++}}. Код згідно з ЄДРПОУ замовника: {{ $procuringEntity->identifier->id }}</p>
    
        <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
            <tr valign="top">
                <td width="30%">{{$n++}}. Конкретна назва предмета закупівлі:</td>
                <td width="20%">{{$n++}}. Коди відповідних класифікаторів предмета закупівлі (за наявності)</td>
                <td width="10%">{{$n++}}. Код згідно з КЕКВ (для бюджетних коштів</td>
                <td width="10%">{{$n++}}. Розмір бюджетного призначення за кошторисом або очікувана вартість предмета закупівлі</td>
                <td width="5%">{{$n++}}. Процедура закупівлі</td>
                <td width="5%">{{$n++}}. Орієнтовний початок проведення процедури закупівлі</td>
                <td width="20%">{{$n++}}. Примітки</td>            
            </tr>
            @include('partials/print/plan/list-table', [
                'items'=>$main
            ])
        </table>
    @endif

    @if($additional)
        <br><br><br>
        <center>
            <h2>ДОДАТОК ДО РІЧНОГО ПЛАНУ ЗАКУПІВЕЛЬ </h2>
            <div>на {{ $budget->year }} рік</div>
        </center>
        <br><br>
    
        <?php $n=1; ?>
    
        <p>{{$n++}}. Найменування замовника: {{ !empty($procuringEntity->identifier->legalName) ? $procuringEntity->identifier->legalName : $procuringEntity->name }}</p>
        <p>{{$n++}}. Код згідно з ЄДРПОУ замовника: {{ $procuringEntity->identifier->id }}</p>
    
        <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
            <tr valign="top">
                <td width="30%">{{$n++}}. Конкретна назва предмета закупівлі:</td>
                <td width="20%">{{$n++}}. Коди відповідних класифікаторів предмета закупівлі (за наявності)</td>
                <td width="10%">{{$n++}}. Код згідно з КЕКВ (для бюджетних коштів</td>
                <td width="10%">{{$n++}}. Розмір бюджетного призначення за кошторисом або очікувана вартість предмета закупівлі</td>
                <td width="5%">{{$n++}}. Процедура закупівлі</td>
                <td width="5%">{{$n++}}. Орієнтовний початок проведення процедури закупівлі</td>
                <td width="20%">{{$n++}}. Примітки</td>            
            </tr>
            @include('partials/print/plan/list-table', [
                'items'=>$additional
            ])
        </table>
    @endif
@endsection