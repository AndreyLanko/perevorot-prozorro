@if(!empty($item->__contract_ongoing) && $item->__contract_ongoing->status=='terminated')
    <div class="container wide-table">
        <div class="row margin-bottom-xl">
		    <h3>Виконання договору</h3>
            <div style="margin-top:-10px;margin-bottom:40px">Друкувати звіт про виконання договору про закупівлю <a href="{{href('tender/'.$item->tenderID.'/print/contract-ongoing/pdf/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/contract-ongoing/html/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id))}}" target="_blank">HTML</a></div>
            
            <div class="row">
                <table class="tender--customer tender--customer--table margin-bottom">
                    <tbody>
                        <tr>
                            <td class="col-sm-8"><strong>Строк дії за договором:</strong></td>
                            <td class="col-sm-4"><strong>{{!empty($item->__contract_ongoing->period->startDate) ? date('d.m.Y', strtotime($item->__contract_ongoing->period->startDate)) : 'не вказанa'}} — {{date('d.m.Y', strtotime($item->__contract_ongoing->period->endDate))}}</strong></td>
                        </tr>
                        <tr>
                            <td class="col-sm-8">Сума оплати за договором:</td>

                            <td class="col-sm-4">
                                {{str_replace('.00', '', number_format($item->__contract_ongoing->amountPaid->amount, 2, '.', ' '))}} 
                                <div class="td-small grey-light">{{$item->__contract_ongoing->amountPaid->currency}}{{$item->__contract_ongoing->amountPaid->valueAddedTaxIncluded?trans('tender.vat'):''}}</div>                                            
                            </td>
                        </tr>
                        @if(!empty($item->__contract_ongoing->terminationDetails))
                            <tr>
                                <td class="col-sm-8">Причини розірвання договору:</td>
                                <td class="col-sm-4">{{$item->__contract_ongoing->terminationDetails}}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>         
        </div>
    </div>
@endif