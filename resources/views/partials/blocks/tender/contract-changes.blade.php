@if(!empty($item->__contracts_changes))
    <div class="col-sm-9 tender--customer--inner margin-bottom">
        <div class="row">
		    <h3>Зміни до договору</h3>
	        @foreach($item->__contracts_changes as $document)
	            <div class="row">
	                <table class="tender--customer tender--customer--table margin-bottom">
	                    <tbody>
	                        <tr>
	                            <td class="col-sm-8"><strong>Дата внесення змін до договору:</strong></td>
	                            <td class="col-sm-4"><strong>{{date('d.m.Y H:i', strtotime($document->date))}}</strong></td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Випадки для внесення змін до істотних умов договору:</td>
	                            <td class="col-sm-4">{{implode('<br>', $document->rationaleTypes)}}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Опис змін, що внесені до істотних умов договору:</td>
	                            <td class="col-sm-4">{{$document->rationale}}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Номер договору про закупівлю:</td>
	                            <td class="col-sm-4">{{$document->contractNumber}}</td>
	                        </tr>
	                        <tr>
	                            <td class="col-sm-8">Договір:</td>
	                            <td class="col-sm-4"><a href="{{$document->contract->url}}" target="_blank">{{$document->contract->title}}</a></td>
	                        </tr>
	                    </tbody>
	                </table>
	            </div>         
	        @endforeach
        </div>
    </div>
@endif