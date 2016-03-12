<div class="col-sm-4 margin-bottom ">
    <h3>{{trans('tender.dates')}}</h3>
    <div class="gray-bg padding margin-bottom">
        <ul class="nav nav-list">
            @if(!empty($item->enquiryPeriod->endDate))
                <li>
                    <strong>{{trans('tender.period1')}}:</strong><br>
                    {{trans('tender.from')}} {{date('d.m.Y H:i', strtotime($item->enquiryPeriod->startDate))}}<br>
                    {{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->enquiryPeriod->endDate))}}
                </li>
            @endif
            @if(!empty($item->complaintPeriod->startDate))
                <li>
                    <strong>{{trans('tender.period4')}}:</strong><br>
                    {{trans('tender.from')}} {{date('d.m.Y H:i', strtotime($item->complaintPeriod->startDate))}}<br>
                    {{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->complaintPeriod->endDate))}}
                </li>
            @endif
            @if(!empty($item->tenderPeriod->endDate))
                <li>
                    <strong>{{trans('tender.period2')}}:</strong><br>
                    {{trans('tender.from')}} {{date('d.m.Y H:i', strtotime($item->tenderPeriod->startDate))}}<br>
                    {{trans('tender.till')}} {{date('d.m.Y H:i', strtotime($item->tenderPeriod->endDate))}}
                </li>
            @endif
            @if(!empty($item->auctionPeriod->startDate))
                <li>
                    <strong>{{trans('tender.period3')}}:</strong><br>
                    {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}
                </li>
            @endif
        </ul>
    </div>
</div>