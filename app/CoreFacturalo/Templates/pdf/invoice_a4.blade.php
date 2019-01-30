@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<html>
<head>
    <title>{{ $document->number_full }}</title>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="{{ $company->name }}" style="max-width: 300px">
            </td>
        @endif
        <td width="80%" class="pl-3">
            <div class="text-left">
                <h2 class="">{{ $company->name }}</h2>
                <h3>{{ 'RUC '.$company->number }}</h3>
                <h4>{{ ($establishment->address !== '-')? $establishment->address : '' }}</h4>
                <h4>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h4>
                <h4>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h4>
            </div>
        </td>
        <td width="45%" class="border-box p-4 text-center">
            <h3 class="text-center">{{ $document->document_type->description }}</h3>
            <h2 class="text-center">{{ $document->number_full }}</h2>
        </td>
    </tr>
</table>
<table class="full-width mt-5">
    <tr>
        <td width="55%">
            <table class="">
                <tbody>
{{--<<<<<<< HEAD--}}
                {{--<tr>--}}
                    {{--<td width="50%">Fecha de emisión: </td>--}}
                    {{--<td width="50%">{{ $document->date_of_issue->format('Y-m-d') }}</td>--}}
                {{--</tr>--}}
                {{--@if($invoice->date_of_due)--}}
                {{--<tr>--}}
                    {{--<td width="50%">Fecha de vencimiento: </td>--}}
                    {{--<td width="50%">{{ $invoice->date_of_due->format('Y-m-d') }}</td>--}}
                {{--</tr>--}}
                {{--@endif--}}
                {{--<tr>--}}
                    {{--<td width="20%">Cliente:</td>--}}
                    {{--<td width="80%">{{ $customer->name }}</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td width="20%">{{ $customer->identity_document_type->description }}:</td>--}}
                    {{--<td width="80%">{{ $customer->number }}</td>--}}
                {{--</tr>--}}
                {{--@if ($customer->address !== '')--}}
                {{--<tr>--}}
                    {{--<td width="20%">Dirección:</td>--}}
                    {{--<td width="80%">{{ $customer->address }}</td>--}}
                {{--</tr>--}}
                {{--@endif--}}
{{--=======--}}
                    {{--<tr>--}}
                        {{--<td width="50%">Fecha de emisión: </td>--}}
                        {{--<td width="50%" class="align-top">{{ $document->date_of_issue->format('Y-m-d') }}</td>--}}
                    {{--</tr>--}}
                    {{--@if($invoice->date_of_due)--}}
                        {{--<tr>--}}
                            {{--<td width="50%">Fecha de vencimiento: </td>--}}
                            {{--<td width="50%" class="align-top">{{ $invoice->date_of_due->format('Y-m-d') }}</td>--}}
                        {{--</tr>--}}
                    {{--@endif--}}
                    {{--<tr>--}}
                        {{--<td width="20%">Cliente:</td>--}}
                        {{--<td width="80%" class="align-top">{{ $customer->name }}</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td width="20%">{{ $customer->identity_document_type->description }}:</td>--}}
                        {{--<td width="80%" class="align-top">{{ $customer->number }}</td>--}}
                    {{--</tr>--}}
{{-->>>>>>> e8a5b610a76971f8a9856f93cddb0c34acc6ae25--}}
                </tbody>
            </table>
        </td>
        <td width="45%" class="align-top">
            <table class="">
                <tbody>
                @if ($document->purchase_order)
                    <tr>
                        <td width="50%">Orden de Compra: </td>
                        <td width="50%">{{ $document->purchase_order }}</td>
                    </tr>
                @endif
                @if ($document->guides)
                    @foreach($document->guides as $guide)
                        <tr>
                            <td>{{ $guide->document_type_id }}</td>
                            <td>{{ $guide->number }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%">
            <table class="">
                <tbody>
                    @if ($customer->address !== '')
                    <tr>
                        <td width="25%">Dirección:</td>
                        <td width="75%" class="align-top">{{ $customer->address }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center"><h6>CANT.</h6></th>
        <th class="border-top-bottom text-center"><h6>UNIDAD</h6></th>
        <th class="border-top-bottom text-left"><h6>DESCRIPCIÓN</h6></th>
        <th class="border-top-bottom text-right"><h6>P.UNIT</h6></th>
        <th class="border-top-bottom text-right"><h6>TOTAL</h6></th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $row->quantity }}</td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-left">
                {!! $row->item->description !!}
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                    @endforeach
                @endif
            </td>
            <td class="text-right">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="full-width">
    <tr>
        <td width="65%">
            <div class="text-left"><img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></div>
            <p>Código Hash: {{ $document->hash }}</p>
            @foreach($document->legends as $row)
                <p class="font-bold">Son: {{ $row->value }} {{ $document->currency_type->description }}</p>
            @endforeach
        </td>
        <td width="35%">
            <table class="" width="100%">
                <tbody>
                @if($document->total_exportation > 0)
                    <tr>
                        <td class="text-right font-bold" width="70%">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold" width="30%">{{ number_format($document->total_exportation, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_free > 0)
                    <tr>
                        <td class="text-right font-bold" width="70%">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold" width="30%">{{ number_format($document->total_free, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_unaffected > 0)
                    <tr>
                        <td class="text-right font-bold" width="70%">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold" width="30%">{{ number_format($document->total_unaffected, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_exonerated > 0)
                    <tr>
                        <td class="text-right font-bold" width="70%">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold" width="30%">{{ number_format($document->total_exonerated, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_taxed > 0)
                    <tr>
                        <td class="text-right font-bold" width="70%">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold" width="30%">{{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-right font-bold" width="70%">IGV: {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold" width="30%">{{ number_format($document->total_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-bold" width="70%">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-bold" width="30%">{{ number_format($document->total, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>