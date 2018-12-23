@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $optional = $document->optional;
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
    $currency_type_description_array = [
        'PEN' => 'S/D',
        '0' => 'S/D',
        '1' => 'DNI',
        '6' => 'RUC',
    ];
    $document_type_description = $document_type_description_array[$document->document_type_code];

    $currency = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('02', $document->currency_type_code);
    $accounts = \App\Models\Tenant\BankAccount::all();
@endphp
<html>
<head>
    <title>{{ $document_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        html {
            font-family: sans-serif;
            font-size: 8px;
        }
        body {
            margin-top: 20px;
        }
        .company_logo {
            max-height: 40px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-lg {
            font-size: 13px;
        }
        .font-xlg {
            font-size: 15px;
        }
        .font-xs {
            font-size: 8px;
        }
        .border-top {
            border-top: 1px solid #000;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
        }
        .ticket {
            width: 94%;
            margin: 0 auto;
        }
        .ticket table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }
        .pl-1 {
            padding-left: 10px;
        }
        .pr-1 {
            padding-right: 10px;
        }
        .vertical-top {
            vertical-align: top;
        }
        /*.ticket > table tr td:first-child {*/
            /*padding-left: 20px;*/
        /*}*/
        /*.ticket > table tr td:last-child {*/
            /*padding-right: 20px;*/
        /*}*/
    </style>
</head>
<body>
<div class="ticket">
    <table>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        @if($company->logo)
        <tr>
            <td class="text-center" colspan="4">
                <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="company_logo">
            </td>
        </tr>
        @endif
        <tr>
            <td class="text-center font-xs" colspan="4"><b>{{ $company->name }}</b></td>
        </tr>
        <tr>
            <td class="text-center font-xs" colspan="4">{{ strtoupper($establishment->getAddressFullAttribute()) }}</td>
        </tr>
        <tr>
            <td class="text-center font-xs" colspan="4">{{ 'RUC '.$company->number }}</td>
        </tr>
        <tr>
            <td class="text-center font-xs" colspan="4">{{ $document_type_description }} ELECTRÓNICA</td>
        </tr>
        <tr>
            <td class="text-center font-xlg" colspan="4">{{ $document_number }}</td>
        </tr>
        <tr>
            <td class="pl-1 pr-1" colspan="4">
                <table>
                    <tr>
                        <td width="80px">{{ $identity_document_type_description_array[$customer->identity_document_type->code] }}</td>
                        <td width="5px">:</td>
                        <td>{{ $customer->number }}</td>
                    </tr>
                    <tr>
                        <td width="80px" class="vertical-top">NOMBRES</td>
                        <td width="5px" class="vertical-top">:</td>
                        <td>{{ $customer->name }}</td>
                    </tr>
                    @if ($customer->getAddressFullAttribute() !== '')
                    <tr>
                        <td width="80px" class="vertical-top">DIRECCIÓN</td>
                        <td width="5px" class="vertical-top">:</td>
                        <td>{{ $customer->getAddressFullAttribute() }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td width="80px">FECHA EMISIÓN</td>
                        <td width="5px">:</td>
                        <td>{{ $document->date_of_issue->format('d/m/Y')  }}</td>
                    </tr>
                    @if($document_base->date_of_due)
                        <tr>
                            <td width="80px">FECHA VENCIMIENTO</td>
                            <td width="5px">:</td>
                            <td>{{ $document_base->date_of_due->format('d/m/Y')  }}</td>
                        </tr>
                    @endif
                    @if ($optional->salesman)
                    <tr>
                        <td width="80px">VENDEDOR</td>
                        <td width="5px">:</td>
                        <td>{{ $optional->salesman  }}</td>
                    </tr>
                    @endif

                    @if ($optional->box_number)
                    <tr>
                        <td width="80px">N° CAJA</td>
                        <td width="5px">:</td>
                        <td>{{ $optional->box_number  }}</td>
                    </tr>
                    @endif

                    @if ($optional->method_payment)
                    <tr>
                        <td width="80px">COND. DE PAGO</td>
                        <td width="5px">:</td>
                        <td>{{ $optional->method_payment  }}</td>
                    </tr>
                    @endif

                    @if($document_base->purchase_order)
                        <tr>
                            <td width="80px">ORDEN C/S: </td>
                            <td width="5px">:</td>
                            <td>{{ $document_base->purchase_order }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td class="pl-1 pr-1" colspan="4">
                <table>
                    <thead>
                    <tr>
                        <td class="text-center">CANT.</td>
                        <td>DESCRIPCIÓN</td>
                        <td class="text-right">VAL.UNIT.</td>
                        <td class="text-right">DSCTO.</td>
                        <td class="text-right">TOTAL</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($details as $row)
                        <tr>
                            <td class="text-center">{{ $row->quantity }}</td>
                            <td>
                                {!! $row->item_description !!}
                                @foreach($row->additional as $add)
                                    <br/>{!! $add->name !!} : {{ $add->value }}
                                @endforeach
                            </td>
                            <td class="text-right">{{ number_format($row->unit_value, 2) }}</td>
                            <td class="text-right">{{ number_format($row->discount_percentage * 100, 0) }}%</td>
                            <td class="text-right">{{ number_format($row->unit_value * (1 - $row->discount_percentage) * $row->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="4">GRAVADA : </td>
                        <td class="text-right">{{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4">IGV :</td>
                        <td class="text-right">{{ number_format($document->total_igv, 2) }}</td>
                    </tr>
                    @if($document->total_other_charges > 0)
                        <tr>
                            <td class="text-right" colspan="4">OTROS CARGOS : </td>
                            <td class="text-right">{{ number_format($document->total_other_charges, 2) }}</td>
                        </tr>
                    @endif
                    {{--<tr>--}}
                        {{--<td class="text-right" colspan="3">RECARGO(10%) :</td>--}}
                        {{--<td class="text-right">{{ number_format($document->total_igv, 2) }}</td>--}}
                    {{--</tr>--}}
                    <tr>
                        <td class="text-right" colspan="4">TOTAL :</td>
                        <td class="text-right">{{ number_format($document->total, 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td class="text-center pl-1 pr-1"  colspan="4">SON: {{ $document->number_to_letter }} {{ $currency->description }}</td>
        </tr>
        @if(isset($document->optional->observations))
            <tr>
                <td class="pl-1 pr-1" colspan="4">OBSERVACIONES</td>
            </tr>
            <tr>
                <td class="pl-1 pr-1" colspan="4">{{ $document->optional->observations }}</td>
            </tr>
        @endif
        @if($document->note)
            @php
                $document_affected = $document_base->affected_document_series.'-'.$document_base->affected_document_number;
                $noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$document_base->note_type_code);
            @endphp
            <tr>
                <td class="pl-1 pr-1" colspan="4">
                    <table>
                        <tr>
                            <td>DOCUMENTO AFECTADO</td>
                            <td>:</td>
                            <td>{{ $document_affected }}</td>
                        </tr>
                        <tr>
                            <td>TIPO DE NOTA</td>
                            <td>:</td>
                            <td>{{ $noteType->description }}</td>
                        </tr>
                        <tr>
                            <td>DESCRIPCION</td>
                            <td>:</td>
                            <td>{{ $document_base->description }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td class="text-center" colspan="4">Resumen: {{ $document->hash }}</td>
        </tr>
        <tr>
            <td class="text-center" colspan="4"><img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></td>
        </tr>
        @foreach($accounts as $account)
            <tr>
                <td class="text-center" colspan="4">{{$account->bank->description}} {{$account->currency_type->description}} {{$account->number}}</td>
            </tr>
        @endforeach
        <tr>
            <td class="font-xs text-center" colspan="4">Para consultar el comprobante ingresar a {!! url('/') !!}/search</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
</div>
</body>
</html>