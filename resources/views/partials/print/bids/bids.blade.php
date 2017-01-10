<?php
    usort($item->__bids, function ($a, $b)
    {
        $datea = !empty($a->date) ? new \DateTime($a->date) : new \DateTime();
        $dateb = !empty($b->date) ? new \DateTime($b->date) : new \DateTime();

        return $datea>$dateb;
    });
?>
@foreach($lot->__bids as $bid)
    <tr valign="top">
        <td>
            @if(!empty($bid->tenderers[0]->identifier->legalName))
                {{$bid->tenderers[0]->identifier->legalName}}
            @else
                {{$bid->tenderers[0]->identifier->name}}
            @endif
        </td>
        <td>
            {{$bid->tenderers[0]->identifier->id}}
        </td>
        <td>
            @if (!empty($lot->__initial_bids_dates[$bid->id]))
                {{date('d.m.Y H:i', strtotime($lot->__initial_bids_dates[$bid->id]))}}<br>
            @else
                @if(!empty($bid->date))
                    {{date('d.m.Y H:i', strtotime($bid->date))}}
                @else
                    не вказана
                @endif
            @endif
        </td>
    </tr>
@endforeach