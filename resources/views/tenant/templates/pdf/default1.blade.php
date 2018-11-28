@php
    $isNote = (in_array($document->document_type_code, ['07', '08']));
    if($isNote) {
        $document_base = $document->note;
    } else {
        $document_base = $document->invoice;
    }
    $company = $document->company;
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $document_type_description_array = [
        '01' => 'FACTURA',
        '02' => 'BOLETA DE VENTA',
        '07' => 'NOTA DE CREDITO',
        '08' => 'NOTA DE DEBITO',
    ];
    $identity_document_type_description_array = [
        '1' => 'DNI',
        '6' => 'RUC',
    ];
    $document_type_description = $document_type_description_array[$document->document_type_code];
@endphp
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet" />
</head>
<body class="white-bg">
<table width="100%">
    <tbody><tr>
        <td style="padding:30px; !important">
            <table width="100%" height="200px" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td width="50%" height="90" align="center">
                        <span><img src="data:image/jpeg;base64,{{ $document->optional->logo }}" height="80" style="text-align:center" border="0"></span>
                    </td>
                    <td width="5%" height="40" align="center"></td>
                    <td width="45%" rowspan="2" valign="bottom" style="padding-left:0">
                        <div class="tabla_borde">
                            <table width="100%" border="0" height="200" cellpadding="6" cellspacing="0">
                                <tbody><tr>
                                    <td align="center">
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:29px" text-align="center">{{ $document_type_description }}</span>
                                        <br>
                                        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:19px" text-align="center">E L E C T R Ó N I C A</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <span style="font-size:15px" text-align="center">R.U.C.: {{ $company->number }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        No.: <span>{{ $document_number }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="padding-left:0">
                        <div class="tabla_borde">
                            <table width="96%" height="100%" border="0" border-radius="" cellpadding="9" cellspacing="0">
                                <tbody><tr>
                                    <td align="center">
                                        <strong><span style="font-size:15px">{{ $company->name }}</span></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody></table>
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody><tr>
                        <td width="60%" align="left"><strong>Razón Social:</strong>  {{ $customer->name }}</td>
                        <td width="40%" align="left">
                            <strong>{{ $identity_document_type_description_array[$customer->identity_document_type_code] }}:</strong>
                            {{ $customer->number }}
                        </td>
                    </tr>
                    <tr>
                        <td width="60%" align="left">
                            <strong>Fecha Emisión: </strong>  {{ $document->date_of_issue->format('d/m/Y') }}
                        </td>
                        <td width="40%" align="left">
                            <strong>Dirección: </strong>
                        </td>
                    </tr>
                    @if ($isNote)
                    <tr>
                        <td width="60%" align="left">
                            <strong>Tipo Doc. Ref.: </strong>
                            {{ $document_base->affected_document_type_code }}
                        </td>
                        <td width="40%" align="left">
                            <strong>Documento Ref.: </strong>
                            {{ $document_base->affected_document_series.'-'.$document_base->affected_document_number }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td width="60%" align="left">
                            <strong>Tipo Moneda: </strong>
                        </td>
                        <td width="40%" align="left">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="tabla_borde">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tbody>
                    <tr>
                        <td align="center" class="bold">Cantidad</td>
                        <td align="center" class="bold">Código</td>
                        <td align="center" class="bold">Descripción</td>
                        <td align="center" class="bold">Valor Unitario</td>
                        <td align="center" class="bold">Valor Total</td>
                    </tr>
                    @foreach($details as $row)
                    <tr class="border_top">
                        <td align="center">
                            {{ $row->quantity }}
                            {{ $row->unit_type_code }}
                        </td>
                        <td align="center">
                            {{ $row->item_id }}
                        </td>
                        <td align="center" width="300px">
                            <span>{{ $row->item_description }}</span>
                            <br>
                        </td>
                        <td align="center">
                            {{ $row->unit_value }}
                        </td>
                        <td align="center">
                            {{ $row->total }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td width="50%" valign="top">
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tbody>
                            <tr>
                                <td colspan="4">
                                    <br>
                                    <br>
                                    <span style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" text-align="center">
                                        {{--@php--}}
                                            {{--$legend = collect($legends)->where('code', '1000')->first();--}}
                                        {{--@endphp--}}
                                        {{--<strong>{{ $legend->description  }}</strong>--}}
                                    </span>
                                    <br>
                                    <br>
                                    <strong>Información Adicional</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tbody>
                            <tr class="border_top">
                                <td width="30%" style="font-size: 10px;">
                                    LEYENDA:
                                </td>
                                <td width="70%" style="font-size: 10px;">
                                    <p>
                                        {{--@foreach($legends as $legend)--}}
                                            {{--@if($legend->code !== '1000')--}}
                                                {{--{{ $legend->description }}<br>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    </p>
                                </td>
                            </tr>
                            @if ($isNote)
                            <tr class="border_top">
                                <td width="30%" style="font-size: 10px;">
                                    MOTIVO DE EMISIÓN:
                                </td>
                                <td width="70%" style="font-size: 10px;">
                                    {{ $document_base->description }}
                                </td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <br>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-valores-totales">
                            <tbody>
                            @if($document->total_taxed > 0)
                            <tr class="border_bottom">
                                <td align="right">
                                    <strong>Op. Gravadas:</strong>
                                </td>
                                <td width="120" align="right">
                                    <span>{{ $document->currenct_type_code }}  {{ $document->total_taxed }}</span>
                                </td>
                            </tr>
                            @endif
                            @if($document->total_unaffected > 0)
                            <tr class="border_bottom">
                                <td align="right">
                                    <strong>Op. Inafectas:</strong>
                                </td>
                                <td width="120" align="right">
                                    <span>{{ $document->currenct_type_code }}  {{ $document->total_unaffected }}</span>
                                </td>
                            </tr>
                            @endif
                            @if($document->total_exonerated > 0)
                            <tr class="border_bottom">
                                <td align="right">
                                    <strong>Op. Exoneradas:</strong>
                                </td>
                                <td width="120" align="right">
                                    <span>{{ $document->currenct_type_code }}  {{ $document->total_exonerated }}</span>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td align="right"><strong>IGV:</strong></td>
                                <td width="120" align="right"><span>{{ $document->currenct_type_code }}  {{ $document->total_igv }}</span></td>
                            </tr>
                            <tr>
                                <td align="right"><strong>Precio Venta:</strong></td>
                                <td width="120" align="right">
                                    <span id="ride-importeTotal" class="ride-importeTotal">{{ $document->currenct_type_code }}  {{ $document->total }}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody></table>
            <br>
            <br>
            <div>
                <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #666; margin: 20px 0; padding: 0;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td width="85%">
                            <blockquote>
                                <strong>Resumen:</strong>   {{ $document->hash }}<br>
                            </blockquote>
                        </td>
                        <td width="15%" align="right">
                            {{--<img src="data:image/png;base64, {{ $document->qr }}" alt="Qr Image"/></td>--}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
    </tbody></table>
</body></html>