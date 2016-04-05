<html>
    <head>
        <style>
            body{
                font-family: dejavu serif;
                font-size: 80%;
            }
            table.border td{
                background-color: #FFF;
            }
            .small{
                font-size:60%;
            }
        </style>
    </head>
    <body>
        <center>
            <h2>ФОРМА ОГОЛОШЕННЯ</h2>
            <p>про проведення відкритих торгів</p>
        </center>

        <br><br>
        
        <table cellpadding="5" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="302">1. Найменування замовника:</td>
                @if (!empty($item->procuringEntity->identifier->legalName))
                    <td><strong>{{$item->procuringEntity->identifier->legalName}}</strong></td>
                @elseif (!empty($item->procuringEntity->name))
                    <td><strong>{{$item->procuringEntity->name}}</strong></td>
                @endif
            </tr>
            <tr>
                <td>2. Код згідно з ЄДРПОУ замовника:</td>
                @if (!empty($item->procuringEntity->identifier->id))
                    <td><strong>{{$item->procuringEntity->identifier->id}}</strong></td>
                @endif
            </tr>
            <tr>
                <td>3. Місцезнаходження замовника:</td>
                @if (!empty($item->procuringEntity->address))
                    <td><strong>{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ' : ''}}{{!empty($item->procuringEntity->address->countryName) ? $item->procuringEntity->address->countryName.',' : '' }}{{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.trans('tender.region') : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</strong></td>
                @endif
            </tr>
            <tr>
                <td>4. Контактна особа замовника, уповноважена здійснювати зв’язок з учасниками:</td>
                @if (!empty($item->procuringEntity->contactPoint))
                    <td><strong>{{!empty($item->procuringEntity->contactPoint->name) ? $item->procuringEntity->contactPoint->name : ''}}{{!empty($item->procuringEntity->contactPoint->telephone) ? ', '.$item->procuringEntity->contactPoint->telephone : ''}}{{!empty($item->procuringEntity->contactPoint->email) ? ', '.$item->procuringEntity->contactPoint->email : ''}}</strong></td>
                @endif
            </tr>
        </table>

        @if(empty($item->lots))
            @include('partials/print/items', [
                'lots'=>[$item],
                '__item'=>$item
            ])
        @else
            @include('partials/print/items', [
                'lots'=>$item->lots,
                '__item'=>$item
            ])
        @endif
    </body>  
</html>