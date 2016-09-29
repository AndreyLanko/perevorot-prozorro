<h3>{{trans('tender.criteria_title')}}</h3>
<table class="tender--customer margin-bottom tender--customer-left margin-bottom-more">
    <tbody>
        <tr class="main-row">
            <td class="col-md-8 col-md-pull-4">{{trans('tender.price')}}:</td>
            <td class="col-md-4 col-md-push-8">{{$item->__features_price*100}}%</td>
        </tr>
        @if(!empty($item->features))
            @foreach($item->features as $feature)
                @if($feature->max>0)
                    <tr class="main-row">
                        <td class="col-md-8 col-md-pull-4">{{!empty($feature->title) ? $feature->title : ''}}:</td>
                        <td class="col-md-4 col-md-push-8 1">{{$feature->max*100}}%</td>
                    </tr>
                    @foreach($feature->enum as $enum)
                        <tr class="add-row">
                            <td class="col-md-8 col-md-pull-4 grey-light">{{$enum->title}}:</td>
                            <td class="col-md-4 col-md-push-8 grey-light">{{$enum->value*100}}%</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
    </tbody>
</table>