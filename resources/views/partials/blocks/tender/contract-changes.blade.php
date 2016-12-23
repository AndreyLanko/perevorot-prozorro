@if(!empty($item->__contracts_changes))
    <div class="container wide-table">
        <div class=" margin-bottom-xl">
		    <h3>Зміни до договору</h3>

	        @foreach($item->__contracts_changes as $document)
	            <div class="row">
	                <table class="tender--customer tender--customer--table margin-bottom-xl">
	                    <tbody>
	                        <tr>
	                            <td class="col-sm-8"><strong>Дата внесення змін до договору:</strong></td>
	                            <td class="col-sm-4"><strong>{{!empty($document->dateSigned)?date('d.m.Y H:i', strtotime($document->dateSigned)):'?'}}</strong></td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Випадки для внесення змін до істотних умов договору:</td>
	                            <td class="col-sm-4">{!!implode('<br>', $document->rationaleTypes)!!}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Опис змін, що внесені до істотних умов договору:</td>
	                            <td class="col-sm-4">{{$document->rationale}}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Номер договору про закупівлю:</td>
	                            <td class="col-sm-4">{{!empty($document->contractNumber) ? $document->contractNumber : 'не вказано'}}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Договір:</td>
	                            <td class="col-sm-4">
    	                                @if(!empty($document->contract))
    	                                    @foreach($document->contract as $contract)
            	                                <div><a href="{{$contract->url}}" target="_blank">{{$contract->title}}</a></div>
        	                                @endforeach
    	                                @else
        	                                не вказано
    	                                @endif
    	                           </td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">
                                    Друкувати повідомлення про внесення змін до договору:
	                            </td>
	                            <td class="col-sm-4">
    	                                <a href="{{href('tender/'.$item->tenderID.'/print/contract-changes/pdf/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id).'?contract='.$document->id)}}" target="_blank">PDF</a> ● <a href="{{href('tender/'.$item->tenderID.'/print/contract-changes/html/'.(!empty($item->lots) && sizeof($item->lots)==1 ? $item->lots[0]->id : $item->id).'?contract='.$document->id)}}" target="_blank">HTML</a>
	                            </td>
	                        </tr>
	                    </tbody>
	                </table>
	            </div>          
	        @endforeach
        </div>
    </div>
@endif