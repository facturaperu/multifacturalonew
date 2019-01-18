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
            <td width="25%">
                <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="company_logo">
            </td>
        @endif
        <td width="100%">
            <table class="">
                <tbody>
                <tr><td class="text-left font-xxlg font-bold">{{ $company->name }}</td></tr>
                <tr><td class="text-left font-xl font-bold">{{ 'RUC '.$company->number }}</td></tr>
                <tr><td class="text-left font-lg">{{ $establishment->address }}</td></tr>
                <tr><td class="text-left font-lg">{{ ($establishment->email !== '-')? $establishment->email : '' }}</td></tr>
                <tr><td class="text-left font-lg font-bold">{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</td></tr>
                </tbody>
            </table>
        </td>
        <td width="30%">
            <table class="border-box">
                <tbody>
                <tr><td class="text-center font-lg">{{ $document->document_type->description }}</td></tr>
                <tr><td class="text-center font-xlg font-bold">{{ $document->number_full }}</td></tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td width="55%" class="align-top">
            <table class="">
                <tbody>
                <tr>
                    <td width="50%">Fecha de emisión: </td>
                    <td width="50%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
                </tr>
                @if($invoice->date_of_due)
                    <tr>
                        <td width="50%">Fecha de vencimiento: </td>
                        <td width="50%">{{ $invoice->date_of_due->format('Y-m-d') }}</td>
                    </tr>
                @endif
                <tr>
                    <td width="20%">Cliente:</td>
                    <td width="80%">{{ $customer->name }}</td>
                </tr>
                <tr>
                    <td width="20%">{{ $customer->identity_document_type->description }}:</td>
                    <td width="80%">{{ $customer->number }}</td>
                </tr>
                @if ($customer->address !== '')
                    <tr>
                        <td width="20%">Dirección:</td>
                        <td width="80%">{{ $customer->address }}</td>
                    </tr>
                @endif
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
</table>
<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">CANT.</th>
        <th class="border-top-bottom text-center">UNIDAD</th>
        <th class="border-top-bottom text-left">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-right">P.UNIT</th>
        <th class="border-top-bottom text-right">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->details as $row)
        <tr>
            <td class="text-center">{{ $row->quantity }}</td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-center">
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
    @endforeach
    </tbody>
</table>
<table class="full-width">
    <tr>
        <td width="35%">
            <table>
                <tbody>
                <tr>
                    <td class="text-center"><img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></td>
                </tr>
                <tr>
                    <td>Código Hash: {{ $document->hash }}</td>
                </tr>
                @foreach($document->legends as $row)
                    <tr>
                        <td class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
        <td width="65%">
            <table class="">
                <tbody>
                @if($document->total_exportation > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_exportation, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_free > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_free, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_unaffected > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_unaffected, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_exonerated > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_exonerated, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_taxed > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-right font-lg font-bold" width="70%">IGV: {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-lg font-bold" width="70%">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                    <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>