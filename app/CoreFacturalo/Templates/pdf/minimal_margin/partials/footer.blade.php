@php
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');

    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = ($document->note) ? $document->note : null;

    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $accounts = \App\Models\Tenant\BankAccount::all();

    if($document_base) {
        $affected_document_number = $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT);
    } else {
        $affected_document_number = null;
    }
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width" style="border-top:2px solid #000">
    <tr>
        @if(in_array($document->document_type->id,['01','03']))
            <td width="30%" style="text-align: top; vertical-align: top;" class="p-3 border-box">
                <div class="">
                    <p><span class="font-bold">BANCO DE CREDITO DEL PERU</span></p>
                    @foreach($accounts as $account)
                        @if($account->bank->description == 'BANCO DE CREDITO DEL PERU')
                            <p>{{$account->currency_type->description}}</p>
                            <p>{{$account->number}}</p>
                            @if($account->cci)
                                <p>CCI: #: {{$account->cci}}</p>
                            @endif
                        @endif
                    @endforeach
                </div>
            </td>
            <td width="30%" style="text-align: top; vertical-align: top;" class="p-3 border-box">
                <p><span class="font-bold">BBVA CONTINENTAL</span></p>
                @foreach($accounts as $account)
                    @if($account->bank->description == 'BBVA CONTINENTAL')
                        <p>{{$account->currency_type->description}}</p>
                        <p>{{$account->number}}</p>
                        @if($account->cci)
                            <p>CCI: #: {{$account->cci}}</p>
                        @endif
                    @endif
                @endforeach
            </td>
        @endif
        <td width="40%" class="p-3" valign="top">
            @foreach(array_reverse( (array) $document->legends) as $row)
                @if ($row->code == "1000")
                    <p class="font-bold">Son: </p>
                    <p class="font-bold" style="text-transform: uppercase;">{{ $row->value }} {{ $document->currency_type->description }}</p>
                @endif
            @endforeach
        </td>
    </tr>
</table>

<table class="full-width" style="text-align: top; vertical-align: top;">
    <tr>
    <td width="40%"></td>
    <td width="20" class="text-center">
        <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -10px;" />
        <p>Timbre Electrónico SUNAT</p>
        <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
    </td>
    <td width="40%" >
        <table class="full-width m-0 py-2 border-box" style="text-align: top; vertical-align: top;">

                @if($document->total_exportation > 0)
                    <tr>
                        <td class="text-right font-bold">OP. EXPORTACIÓN: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_exportation, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_free > 0)
                    <tr>
                        <td class="text-right font-bold">OP. GRATUITAS: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_free, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_unaffected > 0)
                    <tr>
                        <td class="text-right font-bold">OP. INAFECTAS: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_unaffected, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_exonerated > 0)
                    <tr>
                        <td class="text-right font-bold">OP. EXONERADAS: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_exonerated, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_taxed > 0)
                    <tr>
                        <td class="text-right font-bold">OP. GRAVADAS: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_discount > 0)
                    <tr>
                        <td class="text-right font-bold">DESCUENTO TOTAL: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_discount, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_plastic_bag_taxes > 0)
                    <tr>
                        <td class="text-right font-bold">ICBPER: </td>
                        <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_plastic_bag_taxes, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-right font-bold">IGV: </td>
                    <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-bold">IMPORTE TOTAL: </td>
                    <td class="text-right">{{ $document->currency_type->symbol }} {{ number_format($document->total, 2) }}</td>
                </tr>
        </table>
    </td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td class="text-center desc font-bold">Consulta tu boleta en {!! url('/buscar') !!}</td>
    </tr>
</table>
</body>
