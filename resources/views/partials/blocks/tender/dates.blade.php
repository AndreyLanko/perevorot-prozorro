@if($item->procurementMethod=='open' || ($item->procurementMethod=='limited' && in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick'])))
    <div class="col-sm-9 tender--customer--inner margin-bottom margin-bottom-more">
        <h3>Інформація про процедуру</h3>
        <div class="row">
            <table class="tender--customer margin-bottom">
                <tbody>
                    @if($item->procurementMethod=='open')
                        @if(!empty($item->enquiryPeriod->endDate))
                            <tr>
                                <td class="col-sm-8"><strong>{{trans('tender.period1')}}:</strong></td>
                                <td class="col-sm-4">{{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->enquiryPeriod->endDate))}}</td>
                            </tr>
                        @endif
                        @if(!empty($item->complaintPeriod->endDate))
                            <tr>
                                <td class="col-sm-8"><strong>{{trans('tender.period2')}}:</strong></td>
                                <td class="col-sm-4">{{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->complaintPeriod->endDate))}}</td>
                            </tr>
                        @endif
                        @if(!empty($item->tenderPeriod->endDate))
                            <tr>
                                <td class="col-sm-8"><strong>{{trans('tender.period3')}}:</strong></td>
                                <td class="col-sm-4">{{date('d.m.Y H:i', strtotime($item->tenderPeriod->endDate))}}</td>
                            </tr>
                        @endif
                        @if(!empty($item->lots) && sizeof($item->lots)==1 && !empty($item->lots[0]->auctionPeriod->startDate))
                            <tr>
                                <td class="col-sm-8"><strong>{{trans('tender.period4')}}:</strong></td>
                                <td class="col-sm-4">{{date('d.m.Y H:i', strtotime($item->lots[0]->auctionPeriod->startDate))}}</td>
                            </tr>
                        @endif
                        @if(!$item->__isMultiLot && !empty($item->auctionPeriod->startDate))
                            <tr>
                                <td class="col-sm-8"><strong>{{trans('tender.period4')}}:</strong></td>
                                <td class="col-sm-4">{{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</td>
                            </tr>
                        @endif
                        @if (!empty($item->value->amount))
                            <tr>
                                <td class="col-sm-8"><strong>Очікувана вартість:</strong></td>
                                <td class="col-sm-4">{{number_format($item->value->amount, 0, '', ' ')}} {{$item->value->currency}} {{!empty($item->value->valueAddedTaxIncluded)?'з ПДВ':'без ПДВ'}}</td>
                            </tr>
                        @endif
    
                        @if (!empty($item->guarantee) && (int) $item->guarantee->amount>0)
                            <tr>
                                <td class="col-sm-8"><strong>Вид тендерного забезпечення:</strong></td>
                                <td class="col-sm-4">Електронна банківська гарантія</td>
                            </tr>
                            <tr>
                                <td class="col-sm-8"><strong>Сума тендерного забезпечення:</strong></td>
                                <td class="col-sm-4">{{str_replace('.00', '', number_format($item->guarantee->amount, 2, '.', ' '))}} {{$item->guarantee->currency}}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="col-sm-8"><strong>Вид тендерного забезпечення:</strong></td>
                                <td class="col-sm-4">Відсутній</td>
                            </tr>
                        @endif
                        @if(empty($item->lots) || sizeof($item->lots)==1)
                            @if (!empty($item->minimalStep->amount))
                                <tr>
                                    <td class="col-sm-8"><strong>Розмір мінімального кроку пониження ціни:</strong></td>
                                    <td class="col-sm-4">{{number_format($item->minimalStep->amount, 0, '', ' ')}} {{$item->minimalStep->currency}}</td>
                                </tr>
                            @endif
                            @if (!empty($item->value->amount && !empty($item->minimalStep->amount)))
                                <tr>
                                    <td class="col-sm-8"><strong>Розмір мінімального кроку пониження ціни, %:</strong></td>
                                    <td class="col-sm-4">{{str_replace('.00', '', number_format(($item->minimalStep->amount/$item->value->amount)*100, 2, '.', ' '))}} %</td>
                                </tr>
                            @endif
                        @endif
                    @endif
                    @if ($item->procurementMethod=='limited' && in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
                        <tr>
                            <td class="col-sm-8"><strong>Оскарження наміру укласти договір:</strong></td>
                            <td class="col-sm-4">{{!empty($item->__active_award->complaintPeriod) ? 'до '.date('d.m.Y H:i', strtotime($item->__active_award->complaintPeriod->endDate)) : 'Відсутнє'}}</td>
                        </tr>                    
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endif