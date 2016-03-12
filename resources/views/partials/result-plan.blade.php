<tr>
    <td>
        <a href="{{href('plan/'.$item->planID)}}">
            @if(!empty($item->procuringEntity->identifier->legalName))
                {{$item->procuringEntity->identifier->legalName}}<br>
            @elseif(!empty($item->procuringEntity->name))
                {{$item->procuringEntity->name}}<br>
            @endif
        </a>
        #{{$item->procuringEntity->identifier->id}}
    </td>
    <td>
        @if(!empty($item->classification))
            <div>{{$item->classification->id}}: {{$item->classification->description}}</div><br>
        @endif
        @if(!empty($item->additionalClassifications))
            @foreach ($item->additionalClassifications as $additionalClassification)
                <div>{{$additionalClassification->id}}: {{$additionalClassification->description}}</div>
            @endforeach
        @endif        
    </td>
    <td>
        {{$item->budget->description}}
    </td>
    <td>
        {{number_format($item->budget->amount, 0, '', ' ')}} <span class="small val">{{$item->budget->currency}}</span>
    </td>
    <td>
        @if (!empty($item->tender->tenderPeriod))
            {{date('d.m.Y H:i', strtotime($item->tender->tenderPeriod->startDate))}}
        @endif
    </td>
</tr>
