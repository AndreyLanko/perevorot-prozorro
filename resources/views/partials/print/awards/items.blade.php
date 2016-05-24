<?php $start_n=$n; ?>

@foreach($lots as $k=>$lot)

    @if(!empty($lot->title) && sizeof($lots)>1)
        <center>
            <h3>ЛОТ {{$k+1}} — {{$lot->title}}</h3>
        </center>
        <?php $n=$start_n; ?>
    @endif
    
    @if(!empty($lot->__items) || !empty($item->items))
        <ul>
            @foreach((!empty($lot->__items) ? $lot->__items : $item->items) as $one)
                <li>
                    {!!nl2br($one->description)!!}
                </li>
            @endforeach
        </ul>
        <br>
    @endif
@endforeach