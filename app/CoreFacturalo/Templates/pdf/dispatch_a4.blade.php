@php
    $establishment = $document->establishment;
    $customer = $document->customer;
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
                @if($establishment)
                    <tr><td class="text-left font-md">{{ $establishment->address }}</td></tr>
                    <tr><td class="text-left font-md">{{ ($establishment->email != '-')? $establishment->email : '' }}</td></tr>
                    <tr><td class="text-left font-md font-bold">{{ ($establishment->telephone != '-')? $establishment->telephone : '' }}</td></tr>
                @endif
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
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left">DESTINATARIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Razón Social: {{ $customer->name }}</td>
    </tr>
    <tr>
        <td>RUC: {{ $customer->number }}</td>
    </tr>
    <tr>
        <td>Dirección: {{ $customer->address }}</td>
    </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">ENVIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Fecha Emisión: {{ $document->date_of_issue->format('Y-m-d') }}</td>
        <td>Fecha Inicio de Traslado: {{ $document->date_of_shipping->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>Motivo Traslado: {{ $document->transfer_reason_type->description }}</td>
        <td>Modalidad de Transporte: {{ $document->transport_mode_type->description }}</td>
    </tr>
    <tr>
        <td>Peso Bruto Total({{ $document->unit_type_id }}): {{ $document->total_weight }}</td>
        <td>Número de Bultos: {{ $document->packages_number }}</td>
    </tr>
    <tr>
        <td>P.Partida: {{ $document->origin->location_id }} - {{ $document->origin->address }}</td>
        <td>P.Llegada: {{ $document->delivery->location_id }} - {{ $document->delivery->address }}</td>
    </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">TRANSPORTE</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Razón Social: {{ $document->dispatcher->name }}</td>
        <td>RUC: {{ $document->dispatcher->number }}</td>
    </tr>
    <tbody>
    <tr>
        <td>Número de placa del vehículo: {{ $document->license_plate }}</td>
        <td>Conductor: {{ $document->driver->number }}</td>
    </tr>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Item</th>
        <th class="border-top-bottom text-center">Código</th>
        <th class="border-top-bottom text-left">Descripción</th>
        <th class="border-top-bottom text-center">Unidad</th>
        <th class="border-top-bottom text-right">Cantidad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $row->item->internal_id }}</td>
            <td class="text-left">{{ $row->item_description }}</td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-right">{{ $row->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="full-width">
    <tr>
        <td class="text-bold">Observaciones:</td>
    </tr>
    <tr>
        <td>{{ $document->observations }}</td>
    </tr>
</table>
</body>
</html>