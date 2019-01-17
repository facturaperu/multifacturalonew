@php
    $establishment = $document->establishment;
    $supplier = $document->supplier;
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
                <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="logo">
            </td>
        @endif
        <td width="100%">
            <table class="">
                <tbody>
                <tr><td class="text-left font-xlg font-bold">{{ $company->name }}</td></tr>
                <tr><td class="text-left font-md">{{ $establishment->address }}</td></tr>
                <tr><td class="text-left font-md">{{ ($establishment->email != '-')? $establishment->email : '' }}</td></tr>
                <tr><td class="text-left font-md font-bold">{{ ($establishment->telephone != '-')? $establishment->telephone : '' }}</td></tr>
                </tbody>
            </table>
        </td>
        <td width="30%">
            <table class="border-box">
                <tr><td class="text-center font-lg  font-bold pt-20">{{ 'RUC '.$company->number }}</td></tr>
                <tr><td class="text-center font-lg  font-bold">{{ $document->document_type->description }}</td></tr>
                <tr><td class="text-center font-xlg font-bold pb-20">{{ $document->number_full }}</td></tr>
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
                    <td width="30%">Señor(es):</td>
                    <td width="70%">{{ $supplier->name }}</td>
                </tr>
                <tr>
                    <td width="30%">{{ $supplier->identity_document_type->description }}:</td>
                    <td width="70%">{{ $supplier->number }}</td>
                </tr>
                <tr>
                    <td width="30%">Dirección:</td>
                    <td width="70%">{{ $supplier->address }}</td>
                </tr>
                <tr>
                    <td width="30%">Régimen de retención:</td>
                    <td width="70%">{{ $document->retention_type->description }}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td width="45%" class="align-top">
            <table class="">
                <tbody>
                <tr>
                    <td width="40%">Fecha de emisión: </td>
                    <td width="60%">{{ $document->date_of_issue->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td width="40%">Moneda: </td>
                    <td width="60%">{{ $document->currency_type_id }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Tipo<br/>Comprobante</th>
        <th class="border-top-bottom text-center">Número<br/>Comprobante</th>
        <th class="border-top-bottom text-center">Fecha de<br/>Emisión</th>
        <th class="border-top-bottom text-center">Moneda<br/>Comprobante</th>
        <th class="border-top-bottom text-center">Total<br/>Comprobante</th>
        <th class="border-top-bottom text-center">Tasa %</th>
        <th class="border-top-bottom text-center">Importe<br/>Retención</th>
        <th class="border-top-bottom text-center">Tipo<br/>Cambio</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->documents as $row)
        <tr>
            <td class="text-center">{{ $row->document_type->short }}</td>
            <td class="text-center">{{ $row->series }}-{{ $row->number }}</td>
            <td class="text-center">{{ $row->date_of_issue->format('d/m/Y') }}</td>
            <td class="text-center">{{ $row->currency_type_id }}</td>
            <td class="text-right">{{ $row->total_document }}</td>
            <td class="text-center">{{ $document->retention_type->percentage }}</td>
            <td class="text-right">{{ $row->total_retention }}</td>
            <td class="text-right">{{ $row->exchange_rate->factor }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td class="border-top text-right" colspan="4">Totales({{ $document->currency_type->symbol }})</td>
        <td class="border-top text-right">0.00</td>
        <td class="border-top"></td>
        <td class="border-top text-right">0.00</td>
        <td class="border-top"></td>
    </tr>
    </tfoot>
</table>
<table class="full-width">
    <tr>
        <td>Código Hash: {{ $document->hash }}</td>
    </tr>
    @foreach($document->legends as $row)
    <tr>
        <td class="font-bold">{{ $row->value }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>