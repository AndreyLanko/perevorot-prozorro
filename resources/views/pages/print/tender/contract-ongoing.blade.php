@extends('layouts/print')

@section('pdf')
    <center>
        <h2>ЗВІТ</h2>
        <div>про виконання договору про закупівлю</div>
    </center>

    <br><br>
    <?php
        $n=1;
        $tender=$item;
        
        if(!empty($item->lots) && sizeof($item->lots)>1)
        {
            $item=array_first($item->lots, function($key, $lot) use ($lot_id){
                return $lot->id==$lot_id;
            });
        }

    ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Номер процедури закупівлі в електронній системі:</td>
            <td><strong>{{$tender->tenderID}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Номер договору про закупівлю:</td>
            <td><strong>{{!empty($item->__contract_ongoing->contractNumber) ? $item->__contract_ongoing->contractNumber : 'відсутній'}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Дата укладення договору:</td>
            <td><strong>{{!empty($item->__contract_ongoing->dateSigned) ? date('d.m.Y H:i', strtotime($item->__contract_ongoing->dateSigned)) : 'відсутня'}}</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Ціна договору про закупівлю:</td>
            <td><strong>
                @if(!empty($item->__contract_ongoing->amountPaid->amount))
                    {{str_replace('.00', '', number_format($item->__contract_ongoing->amountPaid->amount, 2, '.', ' '))}} {{$item->__contract_ongoing->amountPaid->currency}}{{$item->__contract_ongoing->amountPaid->valueAddedTaxIncluded?trans('tender.vat'):''}}
                @else
                    відсутня
                @endif
            </strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Найменування замовника:</td>
            <td>
                <strong>
                    @if(!empty($tender->procuringEntity->identifier->legalName))
                        {{$tender->procuringEntity->identifier->legalName}}
                    @else
                        {{$tender->procuringEntity->name}}
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
                    {{!empty($item->__active_award->suppliers[0]->contactPoint->telephone) ? ', тел.: '.$item->__active_award->suppliers[0]->contactPoint->telephone : ''}}{{!empty($item->__active_award->suppliers[0]->contactPoint->faxNumber) ? ', факс: '.$item->__active_award->suppliers[0]->contactPoint->faxNumber : ''}}
                </strong>
            </td>
        </tr>
    </table>
    <br>
    <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
        <tr valign="top">
            <td>{{$n++}}. Конкретна назва предмета закупівлі</td>
            <td>{{$n++}}. Кількість товару або обсяг виконання робіт чи надання послуг за договором</td>
            <td>{{$n++}}. Місце поставки товарів, виконання робіт чи надання послуг</td>
            <td>{{$n++}}. Строк поставки товарів, виконання робіт чи надання послуг за договором</td>
        </tr>
        @foreach((!empty($item->__items) ? $item->__items : $item->items) as $one)
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
                <td class="small">
                    @if(!empty($one->deliveryDate->endDate) || !empty($one->deliveryDate->startDate))
                        @if(!empty($one->deliveryDate->startDate)) від {{date('d.m.Y', strtotime($one->deliveryDate->startDate))}}<br>@endif
                        @if(!empty($one->deliveryDate->endDate)) до {{date('d.m.Y', strtotime($one->deliveryDate->endDate))}}@endif
                    @elseif(!empty($one->deliveryDate))
                        {{date('d.m.Y', strtotime($one->deliveryDate))}}
                    @else
                        Відсутня
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr>
            <td width="302">{{$n++}}. Строк дії договору:</td>
            <td><strong>
	            @if(!empty($item->__contract_ongoing->period->startDate))
		            {{date('d.m.Y', strtotime($item->__contract_ongoing->period->startDate))}}
		            @if(!empty($item->__contract_ongoing->period->endDate))
		                — {{date('d.m.Y', strtotime($item->__contract_ongoing->period->endDate))}}
                    @else
                        відсутня
		            @endif
		        @else
		        	Дата початку дії договору не вказана
				@endif
			</strong></td>			
        </tr>
        <tr>
            <td width="302">{{$n++}}. Сума оплати за договором:</td>
            <td>
                <strong>
                    @if(!empty($item->__contract_ongoing->amountPaid->amount))
                        {{str_replace('.00', '', number_format($item->__contract_ongoing->amountPaid->amount, 2, '.', ' '))}} {{$item->__contract_ongoing->amountPaid->currency}}{{$item->__contract_ongoing->amountPaid->valueAddedTaxIncluded?trans('tender.vat'):''}}
                    @else
                        відсутня
                    @endif
                </strong>
            </td>
        </tr>
        <tr>
            <td width="302">{{$n++}}. Причини розірвання договору, якщо таке мало місце:</td>
            <td valign="top">
                <strong>
                    {{!empty($item->__contract_ongoing->terminationDetails) ? $item->__contract_ongoing->terminationDetails : 'відсутні'}}
                </strong>
            </td>
        </tr>
        
    </table>
@endsection