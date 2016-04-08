@if($item->__is_apply)
    <div class="container wide-table">
        <div class="">
            <div class="tender--platforms border-bottom margin-bottom-xl">
                <h3>{{trans('tender.apply_title')}}</h3>
                {{trans('tender.apply_info')}}
                <div class="tender--platforms--list clearfix">
                    @foreach($item->__is_apply_platforms as $platform)
                        <div class="item">
                            <div class="img-wr">
                                <a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">
                                    <img src="/assets/images/platforms/{{$platform['slug']}}.png" alt="{{strip_tags($platform['name'])}}" title="{{strip_tags($platform['name'])}}">
                                </a>
                            </div>
                            <div class="border-hover">
                                <div class="btn-wr"><a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank" class="btn">{{trans('tender.apply_go')}}</a></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{--<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всіх</a>--}}
            </div>
        </div>
    </div>
@endif