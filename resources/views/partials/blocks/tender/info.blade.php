<div class="margin-bottom">    
    <h3>{{trans('tender.info')}}</h3>
    <div class="row">
        @if (!empty($item->description))
            <div class="col-md-12 description-wr croped">
                <div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
                    {!!nl2br($item->description)!!}
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
    </div>
</div>