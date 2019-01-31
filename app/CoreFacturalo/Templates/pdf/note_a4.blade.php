@php
    $establishment = $document->establishment;
    $customer = $document->customer;

    $document_base = $document->note;
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $document_type_description_array = [
        '01' => 'FACTURA',
        '03' => 'BOLETA DE VENTA',
        '07' => 'NOTA DE CREDITO',
        '08' => 'NOTA DE DEBITO',
    ];
    $identity_document_type_description_array = [
        '-' => 'S/D',
        '0' => 'S/D',
        '1' => 'DNI',
        '6' => 'RUC',
    ];

    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<html>
<head>
    <title>{{ $document_number }}</title>
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
            <h2 class="text-center">{{ $document_number }}</h2>
        </td>
    </tr>
</table>

<table class="full-width mt-5">
    <tr>
        <td width="15%">Cliente:</td>
        <td width="45%">{{ $customer->name }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>{{ $customer->identity_document_type->description }}:</td>
        <td>{{ $customer->number }}</td>
        @if($document->date_of_due)
        <td>Fecha de vencimiento:</td>
        <td>{{ $document->date_of_due->format('Y-m-d') }}</td>
        @endif
    </tr>
    @if ($customer->address !== '')
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">{{ $customer->address }}</td>
    </tr>
    @endif
</table>

<table class="full-width mt-3">
    @if ($document->purchase_order)
        <tr>
            <td width="25%">Orden de Compra: </td>
            <td class="text-left">{{ $document->purchase_order }}</td>
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
</table>

<table class="full-width mt-3">
    <tr>
        <td width="20%">Documento Afectado:</td>
        <td width="20%">{{ $document_base->affected_document->series }}-{{ $document_base->affected_document->number }}</td>
        <td width="25%">Tipo de nota:</td>
        <td width="35%">{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>
    </tr>
    <tr>
        <td class="align-top">Descripción:</td>
        <td class="text-left">{{ $document_base->note_description }}</td>
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
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $row->quantity }}</td>
            <td>{{ $row->item->unit_type_id }}</td>
            <td>
                {!! $row->item->description !!}
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                    @endforeach
                @endif
            </td>
            <td  class="text-right" >{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($row->total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot style="border-top: 1px solid #333;">
    <tr>
        <td colspan="5" class="font-lg font-bold"  style="padding-top: 2rem;">Son: {{ $document->number_to_letter }} {{ $document->currency_type->description }}</td>
    </tr>
    @if(isset($document->optional->observations))
        <tr>
            <td colspan="3"><b>Obsevaciones</b></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="3">{{ $document->optional->observations }}</td>
            <td colspan="2"></td>
        </tr>
    @endif
    </tfoot>
</table>
<table class="voucher-totals">
    <tbody>
    <tr>
        <td width="35%">
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
        <td width="65%">
            <table class="voucher-totals-right">
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