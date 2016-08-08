@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ПОВІДОМЛЕННЯ</h2>
        <div>про внесення змін до договору</div>
    </center>

    <br><br>
    <?php
        $n=1;
        $tender=$item;
        
        if(!empty($item->lots))
        {
            $item=array_first($item->lots, function($key, $lot) use ($lot_id){
                return $lot->id==$lot_id;
            });
        }

        if(!property_exists($item, '__contracts_changes'))
            $item=$tender;

        $contract=array_first($item->__contracts_changes, function($key, $document){
            return $document->id==$_GET['contract'];
        });

    ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Найменування замовника:</td>
            <td>
                <strong>
                    @if(!empty($tender->procuringEntity->identifier->legalName))
                        {{$tender->procuringEntity->identifier->legalName}}
                    @else
                        {{$tender->procuringEntity->identifier->name}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Код згідно з ЄДРПОУ замовника:</td>
            <td><strong>{{$tender->procuringEntity->identifier->id}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Місцезнаходження замовника:</td>
            <td>
                <strong>
                    @if (!empty($tender->procuringEntity->address))
                        {{!empty($tender->procuringEntity->address->postalCode) ? $tender->procuringEntity->address->postalCode.', ' : ''}}{{!empty($tender->procuringEntity->address->countryName) ? $tender->procuringEntity->address->countryName.', ' : '' }}{{!empty($tender->procuringEntity->address->region) ? $tender->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($tender->procuringEntity->address->locality) ? $tender->procuringEntity->address->locality.', ' : ''}}{{!empty($tender->procuringEntity->address->streetAddress) ? $tender->procuringEntity->address->streetAddress : ''}}
                    @endif
                </strong>
            </td>
        </tr>        
        <tr valign="top">
            <td width="302">{{$n++}}. Номер процедури закупівлі в електронній системі:</td>
            <td><strong>{{$tender->tenderID}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Номер договору про закупівлю:</td>
            <td><strong>{{!empty($contract->contractNumber) ? $contract->contractNumber : 'не вказано'}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Дата укладення договору:</td>
            <td><strong>{{date('d.m.Y H:i', strtotime($item->__contract_ongoing->dateSigned))}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Найменування учасника, з яким укладено договір про закупівлю:</td>
            <td><strong>{{!empty($item->__active_award->suppliers[0]->identifier->legalName) ? $item->__active_award->suppliers[0]->identifier->legalName : $item->__active_award->suppliers[0]->name}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Код згідно з ЄДРПОУ/реєстраційний номер облікової картки платника податків учасника, з яким укладено договір про закупівлю:</td>
            <td><strong>{{$item->__active_award->suppliers[0]->identifier->id}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Місцезнаходження (для юридичної особи) або місце проживання (для фізичної особи) учасника, з яким укладено договір про закупівлю, номер телефону:</td>
            <td>
                <strong>
                    @if (!empty($item->__active_award->suppliers[0]->address))
                        {{!empty($item->__active_award->suppliers[0]->address->postalCode) ? $item->__active_award->suppliers[0]->address->postalCode.', ' : ''}}{{!empty($item->__active_award->suppliers[0]->address->countryName) ? $item->__active_award->suppliers[0]->address->countryName.', ' : '' }}{{!empty($item->__active_award->suppliers[0]->address->region) ? $item->__active_award->suppliers[0]->address->region.trans('tender.region') : ''}}{{!empty($item->__active_award->suppliers[0]->address->locality) ? $item->__active_award->suppliers[0]->address->locality.', ' : ''}}{{!empty($item->__active_award->suppliers[0]->address->streetAddress) ? $item->__active_award->suppliers[0]->address->streetAddress : ''}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Дата внесення змін до договору:</td>
            <td><strong>{{date('d.m.Y H:i', strtotime($contract->date))}}</strong></td>
        </tr>
        <tr>
            <td width="302">{{$n++}}. Випадки для внесення змін до істотних умов договору згідно з частиною четвертою статті 36 Закону України «Про публічні закупівлі»:</td>
            <td><strong>{!!implode('<br>', $contract->rationaleTypes)!!}</strong></td>
        </tr>
        <tr>
            <td width="302">{{$n++}}. Опис змін, що внесені до істотних умов договору:</td>
            <td>
                <strong>{{!empty($contract->rationale) ? $contract->rationale : 'відсутні'}}</strong>
            </td>
        </tr>
    </table>
@endsection