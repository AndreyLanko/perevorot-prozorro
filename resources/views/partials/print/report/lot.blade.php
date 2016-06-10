<?php $start_n=$n; ?>

<br>
@foreach($lots as $k=>$lot)

    @if(!empty($lot->title) && sizeof($lots)>1)
        <center>
            <h3>ЛОТ {{$k+1}} — {{$lot->title}}</h3>
        </center>
        <?php $n=$start_n; ?>
    @endif
    
    @if(!empty($lot->__items) || !empty($item->items))
        <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
            <tr valign="top">
                <td>{{$n++}}. Конкретна назва предмета закупівлі</td>
                <td>{{$n++}}. Коди відповідних класифікаторів предмета закупівлі (за наявності)</td>
                <td>{{$n++}}. Кількість товарів або обсяг виконання робіт чи надання послуг</td>
                <td>{{$n++}}. Місце поставки товарів або місце виконання робіт чи надання послуг</td>
                <td>{{$n++}}. Строк поставки товарів, виконання робіт чи надання послуг</td>
            </tr>
            @foreach((!empty($lot->__items) ? $lot->__items : $item->items) as $one)
                <tr valign="top">
                    <td>
                        {{$one->description}}
                    </td>
                    <td>
                        @if (!empty($one->classification))
                            {{trans('tender.cpv')}}: {{$one->classification->id}} — {{$one->classification->description}}
                        @else
                            {{trans('tender.no_cpv')}}
                        @endif
                        <br>
                        @if(!empty($one->additionalClassifications[0]))
                            {{trans('tender.dkpp')}}: {{$one->additionalClassifications[0]->id}} — {{$one->additionalClassifications[0]->description}}
                        @else
                            <br>{{trans('tender.no_dkpp')}}
                        @endif                
                    </td>
                    <td>
                        {{!empty($one->quantity)?$one->quantity:''}} @if(!empty($one->unit->code)){{trans('measures.'.$one->unit->code.'.symbol')}}@endif
                    </td>
                    <td>
                        @if(!empty($one->deliveryAddress->streetAddress))
                            {{$one->deliveryAddress->postalCode}}, {{$one->deliveryAddress->region}}, {{$one->deliveryAddress->locality}}, {{$one->deliveryAddress->streetAddress}}
                        @else
                            Відсутнє
                        @endif
                    </td>
                    <td>
                        @if(!empty($one->deliveryDate->endDate) || !empty($one->deliveryDate->startDate))
                            @if(!empty($one->deliveryDate->startDate)) від {{date('d.m.Y', strtotime($one->deliveryDate->startDate))}}<br>@endif
                            @if(!empty($one->deliveryDate->endDate)) до {{date('d.m.Y', strtotime($one->deliveryDate->endDate))}}@endif
                        @elseif(!empty($one->deliveryDate))
                            {{date('d.m.Y', strtotime($one->deliveryDate))}}
                        @else
                            Відсутній
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        <br>
    @endif

    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Дата оприлюднення оголошення про проведення процедури закупівлі:</td>
            <td>
                <strong>
                    @if(in_array($__item->procurementMethodType, ['aboveThresholdUA', 'aboveThresholdEU']))
                        {{!empty($__item->enquiryPeriod) ? date('d.m.Y H:i', strtotime($__item->enquiryPeriod->startDate)) : 'відсутня'}}
                    @elseif(in_array($__item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                        {{!empty($__item->__active_award->complaintPeriod->startDate) ? date('d.m.Y H:i', strtotime($__item->__active_award->complaintPeriod->startDate)) : 'відсутня'}}
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Кількість учасників процедури закупівлі:</td>
            <td>
                <strong>
                    @if(in_array($__item->procurementMethodType, ['aboveThresholdUA', 'aboveThresholdEU']))
                        {{!empty($lot->__unique_bids) ? $lot->__unique_bids : $__item->__unique_bids}}
                    @elseif(in_array($__item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                        {{!empty($lot->__unique_awards) ? $lot->__unique_awards : $__item->__unique_awards}}
                    @endif
                </strong>
            </td>
        </tr>
    </table>
    <br>
    <table cellpadding="5" cellspacing="1" border="0" width="100%" class="border">
        <tr valign="top">
            <td>{{$n++}}. Найменування  учасників  процедури  закупівлі  (для юридичної особи) або прізвище, ім’я, по батькові (для фізичної особи)</td>
            <td>{{$n++}}. Ціна пропозицій учасника до початку аукціону (ціна пропозиції на переговорах у разі застосування переговорної процедури закупівлі)</td>
            <td>{{$n++}}. Ціна пропозицій учасника після закінчення аукціону</td>
            <td>{{$n++}}. Інформація про наявність і відповідність установленим законодавством вимогам документів, що підтверджують відповідність учасників кваліфікаційним критеріям згідно зі статтею 16 Закону України “Про публічні закупівлі”, та наявність/відсутність обставин, установлених статтею 17 цього Закону</td>
        </tr>

        <?php
            if(!empty($lot->__bids))
                $bids=$lot->__bids;
            elseif(!empty($__item->__bids))
                $bids=$__item->__bids;
            else
                $bids=[];
        ?>
        
        @if(!empty($bids))
            @foreach($bids as $one)
                <tr valign="top">
                    <td>
                        <strong>
                            @if($__item->procurementMethod=='open')
                                @if(!empty($one->tenderers[0]->identifier->legalName))
                                    {{$one->tenderers[0]->identifier->legalName}}<br>
                                @elseif(!empty($one->tenderers[0]->name))
                                    {{$one->tenderers[0]->name}}<br>
                                @endif
                            @elseif($__item->procurementMethod=='limited')
                                @if(!empty($one->suppliers[0]->identifier->legalName))
                                    {{$one->suppliers[0]->identifier->legalName}}<br>
                                @elseif(!empty($one->suppliers[0]->name))
                                    {{$one->suppliers[0]->name}}<br>
                                @endif
                            @endif
                        </strong>
                    </td>
                    <td>
                        <strong>
                            @if($__item->procurementMethod=='open')
                                @if(!empty($one->__initial_bids[$one->id]))
                                    {{str_replace('.00', '', number_format($one->__initial_bids[$one->id], 2, '.', ' '))}}
                                    {{$one->value->currency}}{{$one->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                                @elseif(!empty($one->value))
                                    {{str_replace('.00', '', number_format($one->value->amount, 2, '.', ' '))}}  {{$one->value->currency}}{{$one->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                                @elseif(!empty($one->bids_values[$k]->value))
                                    {{str_replace('.00', '', number_format($one->bids_values[$k]->value->amount, 2, '.', ' '))}} {{$one->bids_values[$k]->value->currency}}{{$one->bids_values[$k]->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                                @else
                                    — 
                                @endif
                            @elseif($__item->procurementMethod=='limited')
                                {{str_replace('.00', '', number_format($one->value->amount, 2, '.', ' '))}} {{$one->value->currency}}{{$one->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                            @endif
                        </strong>
                    </td>
                    <td>
                        <strong>
                            @if(!empty($one->value))
                                {{str_replace('.00', '', number_format($one->value->amount, 2, '.', ' '))}} {{$one->value->currency}}{{$one->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                            @elseif(!empty($lot->bids_values[$k]->value))
                                {{str_replace('.00', '', number_format($one->bids_values[$k]->value->amount, 2, '.', ' '))}} {{$one->bids_values[$k]->value->currency}}{{$one->bids_values[$k]->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                            @endif
                        </strong>
                    </td>
                    <td>
                        <strong>
                            @if(!empty($one->__award)){{--it's award--}}
                                @if(!empty($one->__award->qualified) && !empty($one->__award->eligible))
                                    Відповідає кваліфікаційним критеріям, встановленим в тендерній документації. Відсутні підстави для відмови, установлені  ст. 17 Закону України ”Про публічні закупівлі”
                                @endif
                                @if($one->__award->status=='unsuccessful')
                                    @if(!empty($one->__award->title))
                                        {{$one->__award->title}}<br>
                                    @endif
                                    @if(!empty($one->__award->description))
                                        {{$one->__award->description}}
                                    @endif
                                @elseif($one->__award->status=='pending')
                                    Не розглядався
                                @endif
                            @else
                                Не розглядався
                            @endif
                        </strong>
                    </td>
                </tr>
            @endforeach
        @endif        
    </table>
    <br>

    <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr valign="top">
            <td width="302">{{$n++}}. Дата оприлюднення повідомлення про намір укласти договір:</td>
            <td>
                @if (empty($lot->__active_award) && !empty($__item->lots) && sizeof($__item->lots)==1)
                    @if (!empty($__item->__active_award->__date))
                        <strong>
                            {{$__item->__active_award->__date}}
                        </strong>
                    @endif
                @else                
                    @if (!empty($lot->__active_award->__date))
                        <strong>
                            {{$lot->__active_award->__date}}
                        </strong>
                    @endif
                @endif
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Підстави для прийняття рішення про неукладення договору про закупівлю (у разі якщо в результаті проведення торгів не було укладено договір про закупівлю):</td>
            <td>
                <strong>
                    @if(!empty($lot->__cancellations))
                        @foreach($lot->__cancellations as $cancellation)
                            <div>{{$cancellation->reason}}</div>
                        @endforeach
                    @else
                        Відсутні
                    @endif
                </strong>
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Дата укладення договору про закупівлю:</td>
            <td>
                @if(!empty($__item->__signed_contracts))
                    <strong>@foreach($__item->__signed_contracts as $contract){{date('d.m.Y', strtotime($contract->dateSigned))}}@endforeach</strong>
                @else
                    Відсутні
                @endif
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Найменування учасника (для юридичної особи) або прізвище, ім’я, по батькові (для фізичної особи), з яким укладено договір про закупівлю:</td>
            <td>
                @if(!empty($__item->__signed_contracts))
                    <strong>
                        @foreach($__item->__signed_contracts as $contract)
                            @if(!empty($contract->suppliers[0]->identifier->legalName))
                                {{$contract->suppliers[0]->identifier->legalName}}<br>
                            @elseif(!empty($contract->suppliers[0]->name))
                                {{$contract->suppliers[0]->name}}<br>
                            @endif
                        @endforeach
                    </strong>
                @else
                    Відсутні
                @endif
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Місцезнаходження учасника, з яким укладено договір про закупівлю:</td>
            <td>
                @if(!empty($__item->__signed_contracts))
                    <strong>
                        @foreach($__item->__signed_contracts as $contract)
                            @if (!empty($contract->suppliers[0]->address))
                                {{!empty($contract->suppliers[0]->address->postalCode) ? $contract->suppliers[0]->address->postalCode.', ' : ''}}{{!empty($contract->suppliers[0]->address->countryName) ? $contract->suppliers[0]->address->countryName.', ' : '' }}{{!empty($contract->suppliers[0]->address->region) ? $contract->suppliers[0]->address->region.trans('tender.region') : ''}}{{!empty($contract->suppliers[0]->address->locality) ? $contract->suppliers[0]->address->locality.', ' : ''}}{{!empty($contract->suppliers[0]->address->streetAddress) ? $contract->suppliers[0]->address->streetAddress : ''}}
                            @endif
                        @endforeach
                    </strong>
                @else
                    Відсутні
                @endif
            </td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Інформація про субпідрядника (у разі залучення до виконання робіт або надання послуг):</td>
            <td><strong>Відсутня</strong></td>
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Сума, визначена в договорі про закупівлю:</td>
            <td>
                @if(!empty($__item->__signed_contracts))
                    <strong>
                        @foreach($__item->__signed_contracts as $contract)
                            @if (!empty($contract->value->amount))
                                {{str_replace('.00', '', number_format($contract->value->amount, 2, '.', ' '))}}  {{$contract->value->currency}}{{$contract->value->valueAddedTaxIncluded?trans('tender.vat'):''}}
                            @endif
                        @endforeach
                    </strong>
                @else
                    Відсутні
                @endif 
            </td>               
        </tr>
        <tr valign="top">
            <td width="302">{{$n++}}. Дата оприлюднення оголошення з відомостями про укладену рамкову угоду, за якою укладено договір про закупівлю (у разі проведення закупівлі за рамковими угодами): </td>
            <td><strong>Відсутня</strong></td>
        </tr>
    </table>
@endforeach