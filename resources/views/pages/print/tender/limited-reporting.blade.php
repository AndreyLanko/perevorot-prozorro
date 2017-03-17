@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ЗВІТ</h2>
        <div>про укладені договори<br>{{$item->tenderID}}</div>
    </center>

    <br><br>
    <?php
        $n=1;
        $contract=head($item->contracts);
    ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Дата укладення договору:</td>
            <td><strong>{{!empty($contract->dateSigned) ? date('d.m.Y H:i', strtotime($contract->dateSigned)) : 'відсутня'}}</strong></td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Номер договору:</td>
            <td><strong>{{!empty($contract->contractNumber) ? $contract->contractNumber : 'відсутній'}}</strong></td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Найменування замовника</td>
            <td>
                <strong>
                    @if (!empty($item->procuringEntity->identifier->legalName))
                        {{$item->procuringEntity->identifier->legalName}}
                    @elseif (!empty($item->procuringEntity->name))
                        {{$item->procuringEntity->name}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Код згідно з ЄДРПОУ замовника:</td>
            <td><strong>{{$item->procuringEntity->identifier->id}}</strong></td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Місцезнаходження замовника:</td>
            <td>
                <strong>
                    @if (!empty($item->procuringEntity->address))
                        {{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ' : ''}}{{!empty($item->procuringEntity->address->countryName) ? $item->procuringEntity->address->countryName.', ' : '' }}{{!empty($item->procuringEntity->address->region) ? rtrim($item->procuringEntity->address->region, 'обл.').trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Найменування постачальника товарів, виконавця робіт чи надавача послуг (для юридичної особи) або прізвище, ім’я, по батькові (для фізичної особи), з яким укладено договір:</td>
            <td><strong>{{!empty($contract->suppliers[0]->identifier->legalName) ? $contract->suppliers[0]->identifier->legalName : $contract->suppliers[0]->name}}</strong></td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Код згідно з ЄДРПОУ/реєстраційний номер облікової картки платника податків постачальника товарів, виконавця робіт чи надавача послуг:</td>
            <td><strong>{{$contract->suppliers[0]->identifier->id}}</strong></td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Місцезнаходження постачальника товарів, виконавця робіт чи надавача послуг (для юридичної особи) або місце проживання (для фізичної особи) та номер телефону, телефаксу:</td>
            <td>
                <strong>
                    @if (!empty($contract->suppliers[0]->address))
                        {{!empty($contract->suppliers[0]->address->postalCode) ? $contract->suppliers[0]->address->postalCode.', ' : ''}}{{!empty($contract->suppliers[0]->address->countryName) ? $contract->suppliers[0]->address->countryName.', ' : '' }}{{!empty($contract->suppliers[0]->address->region) ? rtrim($contract->suppliers[0]->address->region, 'обл.').trans('tender.region') : ''}}{{!empty($contract->suppliers[0]->address->locality) ? $contract->suppliers[0]->address->locality.', ' : ''}}{{!empty($contract->suppliers[0]->address->streetAddress) ? $contract->suppliers[0]->address->streetAddress : ''}}
                    @endif

                    @if (!empty($contract->suppliers[0]->contactPoint->telephone))
                        <br>{{$contract->suppliers[0]->contactPoint->telephone}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td>{{$n++}}. Конкретна назва предмета закупівлі:</td>
            <td><strong>{{$item->title}}</strong></td>
        </tr>
    </table>

    @if(!empty($item->awards))
        <br>
        <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
            <tr valign="top">
                <td>{{$n++}}. Найменування (номенклатура, асортимент) товарів, робіт чи послуг</td>
                <td>{{$n++}}. Кількість товарів, робіт чи послуг</td>
                <td>{{$n++}}. Місце поставки товарів, виконання робіт чи надання послуг</td>
                <td>{{$n++}}. Строк поставки товарів, виконання робіт чи надання послуг</td>
            </tr>
            @foreach($item->items as $one)
                <tr valign="top">
                    <td>
                        {{$one->description}}
                    </td>
                    <td>
                        {{!empty($one->quantity)?$one->quantity:''}} @if(!empty($one->unit->code)){{trans('measures.'.$one->unit->code.'.symbol')}}@endif
                    </td>
                    <td>
                        @if(!empty($one->deliveryAddress->streetAddress))
                            {{$one->deliveryAddress->countryName}}, {{$one->deliveryAddress->postalCode}}, {{$one->deliveryAddress->region}}, {{$one->deliveryAddress->locality}}, {{$one->deliveryAddress->streetAddress}}
                        @else
                            Відсутнє
                        @endif
                    </td>
                    <td>
                        {{date('d.m.Y H:i', strtotime($one->deliveryDate->endDate))}}
                    </td>
                </tr>
            @endforeach
        </table>
        <br>
    @endif

    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Інформація про технічні та якісні характеристики товарів, робіт чи послуг:</td>
            <td><strong></strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Ціна договору:</td>
            <td><strong>{{str_replace('.00', '', number_format($contract->value->amount, 2, '.', ' '))}} {{$contract->value->currency}}{{$contract->value->valueAddedTaxIncluded?trans('tender.vat'):''}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Строк дії договору:</td>
            <td>
                <strong>
                    @if(!empty($contract->period->endDate) || !empty($contract->period->startDate))
                        @if(!empty($contract->period->startDate)) від {{date('d.m.Y H:i', strtotime($contract->period->startDate))}}<br>@endif
                        @if(!empty($contract->period->endDate)) до {{date('d.m.Y H:i', strtotime($contract->period->endDate))}}@endif
                    @elseif(!empty($contract->period))
                        {{date('d.m.Y H:i', strtotime($contract->period))}}
                    @else
                        Відсутня
                    @endif
                </strong>
            </td>
        </tr>
    </table>
@endsection