@if(in_array($item->status, ['cancelled', 'unsuccessful']))
    <div class="row">
        <div class="container margin-bottom-xl">
            <div class="col-sm-9">
                <h3>Інформація про відміну</h3>

                @if($item->status=='cancelled' && !empty($item->__cancellations))
                    @foreach($item->__cancellations as $cancellation)
                        <div class="row">
                            <div class="col-md-12 margin-bottom">
                                <strong>Дата скасування</strong>
                                <div>{{date('d.m.Y H:i', strtotime($cancellation->date))}}</div>
                            </div>
                            @if(!empty($cancellation->reason))
                                <div class="col-md-12 margin-bottom">
                                    <strong>Причина скасування</strong>
                                    <div>{{$cancellation->reason}}</div>
                                </div>
                            @endif
                            <div class="col-md-12 margin-bottom">
                                <strong>Документи</strong>
                                @if (!empty($cancellation->documents))
                                    <table class="tender--customer">
                                        <tbody>
                                            @foreach($cancellation->documents as $k=>$document)
                                                <tr>
                                                    <td class="col-sm-2" style="padding-left:0px;">{{!empty($document->dateModified) ? date('d.m.Y H:i', strtotime($document->dateModified)) : trans('tender.no_date')}}</td>
                                                    <td class="col-sm-6"><a href="{{$document->url}}" target="_blank" class="word-break{{!empty($document->stroked) ? ' stroked': ''}}">{{$document->title}}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="tender--customer padding-td">{{trans('tender.no_documents')}}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @elseif(in_array($item->procurementMethodType, ['aboveThresholdEU', 'aboveThresholdUA', 'aboveThresholdUA.defense']) && !empty($item->__unsuccessful_awards))
                    <div class="row">
                        <div class="col-md-12 margin-bottom">
                            <strong>Дата відміни</strong>
                            <div>{{!empty($item->dateModified) ? date('d.m.Y H:i', strtotime($item->dateModified)) : 'не вказано'}}</div>
                        </div>
                        <div class="col-md-12">
                            <strong>Причина відміни</strong>
                            <div>Відхилення всіх тендерних пропозицій згідно з Законом про публічні закупівлі</div>
                        </div>
                    </div>
                @else
                    <?php
                        $numberOfBids=0;

                        if(!empty($item->__bids))
                        {
                            $numberOfBids=array_where($item->__bids, function($key, $bid){
                                return !empty($bid->status) && $bid->status=='active';
                            });
                        
                            $numberOfBids=$numberOfBids ? sizeof($numberOfBids) : 0;
                        }

                        $numberOfQualifications=0;

                        if(!empty($item->__qualifications))
                        {
                            $numberOfQualifications=array_where($item->__qualifications, function($key, $qualification){
                                return !empty($qualification->status) && $qualification->status=='active';
                            });
                            
                            $numberOfQualifications=$numberOfQualifications ? sizeof($numberOfQualifications) : 0;
                        }
                    ?>
                    @if($item->status=='unsuccessful')
                        @if(in_array($item->procurementMethodType, ['aboveThresholdUA']))
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Дата відміни</strong>
                                    <div>{{date('d.m.Y H:i', strtotime($tenderPeriod->endDate))}}</div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>подання для участі в торгах менше двох тендерних пропозицій</div>
                                </div>
                            </div>
                        @elseif(in_array($item->procurementMethodType, ['aboveThresholdUA.defense']))
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Дата відміни</strong>
                                    <div>{{date('d.m.Y H:i', strtotime($tenderPeriod->endDate))}}</div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>не подання для участі в торгах жодної тендерної пропозиції</div>
                                </div>
                            </div>
                        @elseif($item->procurementMethodType=='belowThresholdUA')
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Дата відміни</strong>
                                    <div>{{date('d.m.Y H:i', strtotime($tenderPeriod->endDate))}}</div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>відсутність тендерних пропозицій</div>
                                </div>
                            </div>
                        @elseif($item->procurementMethodType=='aboveThresholdEU' && $numberOfBids < 2)
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Дата відміни</strong>
                                    <div>{{date('d.m.Y H:i', strtotime($tenderPeriod->endDate))}}</div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>подання для участі в торгах менше двох тендерних пропозицій</div>
                                </div>
                            </div>                        
                        @elseif($item->procurementMethodType=='aboveThresholdEU' && $numberOfQualifications < 2)
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Дата відміни</strong>
                                    <div>
                                        @if(!empty($qualificationPeriod->endDate))
                                            {{date('d.m.Y H:i', strtotime($qualificationPeriod->endDate))}}
                                        @else
                                            відсутня
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>допущення до оцінки менше двох тендерних пропозицій</div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12 margin-bottom">
                                    <strong>Торги відмінено</strong>
                                </div>
                                <div class="col-md-12">
                                    <strong>Причина відміни</strong>
                                    <div>відсутність пропозицій</div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
@endif