@if($item->__icon!='pen')
    @if (!empty($item->__questions))
        <div class="container margin-bottom-xl">
            <div class="col-sm-9">
                <h3>{{trans('tender.questions_title')}}</h3>
    
                <div class="row questions">
                    <div class="description-wr questions-block">
                        @foreach($item->__questions as $k=>$question)
                            <div class="questions-row{{$k>1?' none':' visible'}}">
                                <div><strong>{{$question->title}}</strong></div>
                                <div class="grey-light size12 question-date">{{date('d.m.Y H:i', strtotime($question->date))}}</div>
                                @if (!empty($question->description))
                                    <div class="question-one description-wr margin-bottom{{mb_strlen($question->description)>350?' croped':' open'}}">
                                        <div class="description">
                                            {{$question->description}}
                                        </div>
                                        @if (mb_strlen($question->description)>350)
                                            <a class="search-form--open"><i class="sprite-arrow-down"></i>
                                                <span>{{trans('interface.expand')}}</span>
                                                <span>{{trans('interface.collapse')}}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($question->answer))
                                    <div class="answer"><strong>{{trans('tender.answer')}}:</strong> <i>{!!nl2br($question->answer)!!}</i></div>
                                @else
                                    <div class="answer" style="font-weight: bold">{{trans('tender.no_answer')}}</div>
                                @endif
                            </div>
                        @endforeach
                        @if (sizeof($item->__questions)>2)
                            <a class="question--open"><i class="sprite-arrow-down"></i>
                                <span class="question-up">{{trans('tender.expand_questions')}}: {{sizeof($item->questions)}}</span>
                                <span class="question-down">{{trans('tender.collapse_questions')}}</span>
                            </a>
                        @endif                                                
                    </div>
                    {{--trans('tender.no_questions')--}}
                </div>
            </div>
        </div>
    @endif
@endif