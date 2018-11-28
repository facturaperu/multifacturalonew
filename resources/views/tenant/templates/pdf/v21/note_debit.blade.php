@php
    $company = $document->company;
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT)
@endphp
<html>
<head>
    <title>{{ $document_number }}</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-xsm {
            font-size: 10px;
        }
        .font-sm {
            font-size: 12px;
        }
        .font-lg {
            font-size: 13px;
        }
        .font-xlg {
            font-size: 16px;
        }
        .font-xxlg {
            font-size: 22px;
        }
        .font-bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        .voucher-company-right {
            border: 1px solid #333;
        }
        .voucher-company-right tbody tr:first-child td {
            padding-top: 10px;
        }
        .voucher-company-right tbody tr:last-child td {
            padding-bottom: 10px;
        }
        .voucher-information {
            border: 1px solid #333;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .voucher-information tbody tr td {
            padding-top: 5px;
            padding-bottom: 5px;
            vertical-align: top;
        }
        .voucher-information-left tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-information-right tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-details {
        }
        .voucher-details thead tr th {
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 5px 10px;
        }
        .voucher-details thead tr th:first-child {
            border-left: 1px solid #333;
        }
        .voucher-details thead tr th:last-child {
            border-right: 1px solid #333;
        }
        .voucher-details tbody tr td {
            padding: 5px 10px;
            vertical-align: middle;
        }
        .voucher-details tfoot tr td {
            padding: 3px 10px;
        }
        .voucher-totals {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .voucher-totals tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-footer {
            margin-bottom: 30px;
        }
        .voucher-footer tbody tr td{
            border-top: 1px solid #333;
            padding: 3px 10px;
        }
        .company_logo {
            height: 150px;
        }
    </style>
</head>
<body>
<table class="voucher-company">
    <tr>
        <td width="60%">
            <table class="voucher-company-left">
                <tbody>
                <tr><td class="text-center font-xxlg font-bold">{{ $company->name }}</td></tr>
                {{--<tr><td class="text-center font-xxlg font-bold"><img src="data:image/jpeg;base64,{{ $company->logo }}" class="company_logo"></td></tr>--}}
                {{--<tr><td class="text-center">{{ $establishment->address.' - '.--}}
                                               {{--$establishment->location->name }}</td></tr>--}}
                <tr><td class="text-center">{{ $establishment->address }}</td></tr>
                <tr><td class="text-center">{{ $establishment->department }}-{{ $establishment->province }}-{{ $establishment->district }}</td></tr>
                {{--<tr><td class="text-center">Teléfonos: {{ $establishment->phone }}</td></tr>--}}
                {{--<tr><td class="text-center">{{ $establishment->email }}</td></tr>--}}
                {{--<tr><td class="text-center">{{ $company->web }}</td></tr>--}}
                </tbody>
            </table>
        </td>
        <td width="40%">
            <table class="voucher-company-right">
                <tbody>
                <tr><td class="text-center">{{ 'RUC '.$company->number }}</td></tr>
                <tr><td class="text-center font-lg">{{ $document->document_type_code }}</td></tr>
                <tr><td class="text-center font-xlg font-bold">{{ $document_number }}</td></tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="voucher-information">
    <tr>
        <td width="60%">
            <table class="voucher-information-left">
                <tbody>
                <tr><td width="20%">Cliente:</td><td width="80%">{{ $customer->name }}</td></tr>
                <tr><td width="20%">{{ $customer->identity_document_type_code }}:</td><td width="70%">{{ $customer->number }}</td></tr>
                {{--@if (isset($customer->address))--}}
                @if($customer->identity_document_type_code === '6')
                    {{--<tr><td width="20%">Dirección:</td><td width="80%">{{ $customer->address }}-{{ $customer->address }}</td></tr>--}}
                    {{--<tr><td width="20%">Localidad:</td><td width="80%">{{ $customer->department }}-{{ $customer->province }}-{{ $customer->district }}</td></tr>--}}
                @endif
                    {{--<tr><td width="20%">Localidad:</td><td width="80%">{{ $customer->location->name }}</td></tr>--}}
                {{--@endif--}}
                </tbody>
            </table>
        </td>
        <td width="40%">
            <table class="voucher-information-right">
                <tbody>
                    <tr><td width="60%">Fecha de emisión: </td><td width="40%">{{ $document->date_of_issue->format('d/m/Y') }}</td></tr>
                    {{--<tr><td width="60%">Condición: </td><td width="40%">{{ $invoice->optional->payment_condition }}</td></tr>--}}
                    {{--@if (strtolower($invoice->optional->payment_condition) === 'cancelado')--}}
                    {{--<tr><td width="60%">Fecha de cancelación: </td><td width="40%">{{ $document->date_of_issue->format('d/m/Y') }}</td></tr>--}}
                    {{--@endif--}}
                    {{--<tr><td width="60%">Moneda: </td><td width="40%">{{ $document->currency_type->name }}</td></tr>--}}
                    {{--@if ($invoice->purchase_order)--}}
                    {{--<tr><td width="60%">OC/OS: </td><td width="40%">{{ $invoice->purchase_order }}</td></tr>--}}
                    {{--@endif--}}
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="voucher-details">
    <thead>
    <tr>
        <th class="text-center" width="80px">CANTIDAD</th>
        <th width="60px">UNIDAD</th>
        <th>DESCRIPCIÓN</th>
        <th class="text-right" width="80px">P.UNIT</th>
        <th class="text-right" width="80px">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($details as $row)
        <tr>
            <td class="text-center">{!! $row->quantity !!}</td>
            <td>{!! $row->unit_type_code !!}</td>
            <td>{!! $row->item_description !!}</td>
            <td class="text-right">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($row->total, 2) }}</td>
        </tr>
    @endforeach
    <tr><td colspan="5"></td></tr>
    <tr><td colspan="5"></td></tr>
    <tr><td colspan="5"></td></tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="font-lg font-bold">Son: {{ 'sdasd' }} {{ $document->currency_type_code }}</td>
        </tr>
    </tfoot>
</table>
<table class="voucher-totals">
    <tbody>
    <tr>
        <td width="50%">
            <table class="voucher-totals-left">
                {{--<tbody>--}}
                <tr><td class="text-center">
                        <img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></td>
                </tr>
                <tr><td class="text-center">{{ $document->hash }}</td></tr>
                <tr><td class="text-center">Código Hash</td></tr>
                {{--</tbody>--}}
            </table>
        </td>
        <td width="50%">
            <table class="voucher-totals-right">
                <tbody>
                @if($document->total_exonerated > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">Operaciones Exoneradas: {{ $document->currency_type_code }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_exonerated, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_taxed > 0)
                    <tr>
                        <td class="text-right font-lg font-bold" width="70%">Operaciones Gravadas: {{ $document->currency_type_code }}</td>
                        <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-right font-lg font-bold" width="70%">IGV: {{ $document->currency_type_code }}</td>
                    <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-lg font-bold" width="70%">IMPORTE TOTAL: {{ $document->currency_type_code }}</td>
                    <td class="text-right font-lg font-bold" width="30%">{{ number_format($document->total, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table class="voucher-footer">
    <tbody>
    <tr>
        {{--<td class="text-center font-sm">Para consultar el comprobante ingresar a {{ $company->cpe_url }}</td>--}}
    </tr>
    </tbody>
</table>
</body>
</html>