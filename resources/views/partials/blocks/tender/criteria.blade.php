@if($item->__icon!='pen' && $item->procurementMethod=='open')
    @if(empty($item->lots) || sizeof($item->lots)==1)
        <div class="col-sm-9 criterii margin-bottom-more">
            <h3>{{trans('tender.criteria_title')}}</h3>
            <table class="tender--customer margin-bottom tender--customer-left">
                <tbody>
                    <tr class="main-row">
                        <td class="col-md-8 col-md-pull-4">{{trans('tender.price')}}:</td>
                        <td class="col-md-4 col-md-push-8">{{$item->__features_price*100}}%</td>
                    </tr>
                    @if(!empty($item->__features))
                        @foreach($item->__features as $feature)
                            @if($feature->max>0)
                                <tr class="main-row">
                                    <td class="col-md-8 col-md-pull-4">{{!empty($feature->title) ? $feature->title : ''}}:</td>
                                    <td class="col-md-4 col-md-push-8 1">{{$feature->max*100}}%</td>
                                </tr>
                                @foreach($feature->enum as $enum)
                                    @if($enum->value>0)
                                        <tr class="add-row">
                                            <td class="col-md-8 col-md-pull-4 grey-light">{{$enum->title}}:</td>
                                            <td class="col-md-4 col-md-push-8 grey-light">{{$enum->value*100}}%</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    @endif
@endif