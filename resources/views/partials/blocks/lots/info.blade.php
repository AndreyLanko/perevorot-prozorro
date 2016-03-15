<div class="margin-bottom">    
    <div class="row">
        <h3>Інформація про лот</h3>
        <div>Предмет закупівлі:{{!empty($item->title) ? $item->title : 'без назви'}}</div>        
        @if (!empty($item->description))
            <div class="col-md-12 description-wr croped">
                <div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
                    Опис предмету закупівлі: {!!nl2br($item->description)!!}
                </div>
                @if (mb_strlen($item->description)>350)
                    <a class="search-form--open" href="">
                        <i class="sprite-arrow-right"></i>
                        <span>{{trans('interface.expand')}}</span>
                        <span>{{trans('interface.collapse')}}</span>
                    </a>
                @endif
            </div>
        @endif
        <div>Статус: {{trans('tender.lot_status.'.$item->status)}}</div>
        <br>
        @if (!empty($item->value))
            <div>
                Очікувана вартість: <strong>{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></strong>
                @if($item->value->valueAddedTaxIncluded)
                    з ПДВ
                @else
                    без ПДВ
                @endif
            </div>
        @endif
        @if (!empty($item->minimalStep))
            <div>Мінімальний крок аукціону: <strong>{{number_format($item->minimalStep->amount, 0, '', ' ')}} <span class="small">{{$item->minimalStep->currency}}</span></strong>
                @if($item->minimalStep->valueAddedTaxIncluded)
                    з ПДВ
                @else
                    без ПДВ
                @endif
            </div>
        @endif
        
        <h3>Аукціон</h3>
        @if (!empty($item->auctionPeriod->startDate))
            <div>Початок: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</div>
        @endif
        @if (!empty($item->auctionPeriod->endDate))
            <div>Закінчення: {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</div>
        @endif
        @if(!empty($item->auctionUrl))
            <div><a href="{{$item->auctionUrl}}" target="_blank">Перейти на аукціон</a></div>
        @endif
        @if(!in_array($tender->status, ['active.enquiries', 'active.tendering', 'active.pre-qualification', 'active.pre-qualification.stand-still']) && empty($item->auctionPeriod->startDate) && empty($item->auctionPeriod->endDate))
            <div><strong>Аукціон не проводився</strong></div>
        @endif
    </div>
</div>
<br>