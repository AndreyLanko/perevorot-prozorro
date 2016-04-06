@if(in_array($item->procurementMethodType, ['negotiation', 'negotiation.quick']))
    <div class="col-sm-9 margin-bottom margin-bottom-more">
        <h3>Обгрунтування застосування переговорної процедури</h3>
        
        @if (!empty($item->cause))
            <div class="row">
                <div class="col-md-12 margin-bottom">
                    <strong>Пункт закону</strong>
                    <div>{{trans('negotiation.'.$item->cause)}}</div>
                </div>
            </div>
        @endif

        @if (!empty($item->causeDescription))
            <div><strong>Обгрунтування</strong></div>
            <div>{!!nl2br($item->causeDescription)!!}</div>
        @endif
    </div>
@endif