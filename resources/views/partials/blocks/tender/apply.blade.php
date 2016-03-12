@if($item->is_active_proposal)
    <div class="container wide-table">
        <div class="">
            <div class="tender--platforms border-bottom margin-bottom-xl">
                <h3>{{trans('tender.apply_title')}}</h3>
                {{trans('tender.apply_info')}}
                <div class="tender--platforms--list clearfix">
                    @foreach($platforms as $platform)
                        @if ($platform['tender'])
                            <div class="item">
                                <div class="img-wr">
                                    <a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">
                                        <img src="/assets/images/platforms/{{$platform['slug']}}.png" alt="{{$platform['name']}}" title="{{$platform['name']}}">
                                    </a>
                                </div>
                                <div class="border-hover">
                                    <div class="btn-wr"><a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank" class="btn">{{trans('tender.apply_go')}}</a></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{--<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всіх</a>--}}
            </div>
        </div>
    </div>
@endif