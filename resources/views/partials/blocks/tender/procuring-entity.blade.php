@if (!empty($item->procuringEntity))
    <div class="col-sm-9 tender--customer--inner margin-bottom-more">
        @if (Lang::getLocale() == 'ua')
            <h3>Інформація про замовника</h3>
            <h4>Purchasing Body</h4>

            <div class="row">
                <table class="tender--customer margin-bottom">
                    <tbody>
                    @if(!empty($item->procuringEntity->identifier->legalName))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_name')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->identifier->legalName}}</td>
                        </tr>
                    @elseif (!empty($item->procuringEntity->name))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_name')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->name}}</td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->identifier->id))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_code')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->identifier->id}}</td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->contactPoint->url))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_website')}}:</strong></td>
                            <td class="col-sm-6"><a href="{{$item->procuringEntity->contactPoint->url}}" target="_blank">{{$item->procuringEntity->contactPoint->url}}</a></td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->address))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_addr')}}:</strong></td>
                            <td class="col-sm-6">{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ': ''}}{{$item->procuringEntity->address->countryName}}, {{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->contactPoint))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_contact')}}:</strong></td>
                            <td class="col-sm-6">
                                @if (!empty($item->procuringEntity->contactPoint->name))
                                    {{$item->procuringEntity->contactPoint->name}}<br>
                                @endif
                                @if (!empty($item->procuringEntity->contactPoint->telephone))
                                    {{$item->procuringEntity->contactPoint->telephone}}<br>
                                @endif
                                @if (!empty($item->procuringEntity->contactPoint->email))
                                    <a href="mailto:{{$item->procuringEntity->contactPoint->email}}">{{$item->procuringEntity->contactPoint->email}}</a><br>
                                @endif
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @elseif (Lang::getLocale() == 'en' && is_array($item->procurementMethodType, ["aboveThresholdEU", "aboveThresholdUA.defense"]))
            <h3>Purchasing Body</h3>
            <h4>Інформація про замовника</h4>

            <div class="row">
                <table class="tender--customer margin-bottom">
                    <tbody>
                    @if(!empty($item->procuringEntity->identifier->legalName_en))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_name')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->identifier->legalName_en}}</td>
                        </tr>
                    @elseif (!empty($item->procuringEntity->name))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_name')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->name}}</td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->identifier->id))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_code')}}:</strong></td>
                            <td class="col-sm-6">{{$item->procuringEntity->identifier->id}}</td>
                        </tr>
                    @endif
                    @if (!empty($item->procuringEntity->contactPoint))
                        <tr>
                            <td class="col-sm-4"><strong>{{trans('tender.customer_contact')}}:</strong></td>
                            <td class="col-sm-6">
                                @if (!empty($item->procuringEntity->contactPoint->name_en))
                                    {{$item->procuringEntity->contactPoint->name_en}}<br>
                                @endif
                                @if (!empty($item->procuringEntity->contactPoint->telephone))
                                    {{$item->procuringEntity->contactPoint->telephone}}<br>
                                @endif
                                @if (!empty($item->procuringEntity->contactPoint->email))
                                    <a href="mailto:{{$item->procuringEntity->contactPoint->email}}">{{$item->procuringEntity->contactPoint->email}}</a><br>
                                @endif
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif