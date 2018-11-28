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
    $document_type_description = $document_type_description_array[$document->document_type_code];
    $currency = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('02', $document->currency_type_code);
    $accounts = \App\Models\Tenant\BankAccount::all();
@endphp
{{--$idDocument = $document->id;--}}

{{--if(!$note = \App\Models\Tenant\Note::where('document_id', $idDocument)->first()){--}}

{{--} else {--}}
{{--$document_affected = $note->affected_document_series.'-'.$note->affected_document_number;--}}

{{--$noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$note->note_type_code);--}}
{{--}--}}
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
            padding-top: 15px;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }
        .voucher-company-right tbody tr:first-child td {
            padding-top: 10px;
        }
        .voucher-company-right tbody tr:last-child td {
            padding-bottom: 10px;
        }
        .voucher-information {
            border: 1px solid #333;
        }
        .voucher-information.top-note, .voucher-information.top-note tbody tr td {
            border-top: 0;
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
        .voucher-details thead {
            background-color: #f5f5f5;
        }
        .voucher-details thead tr th {
            /*border-top: 1px solid #333;*/
            /*border-bottom: 1px solid #333;*/
            padding: 5px 10px;
        }
        .voucher-details thead tr th:first-child {
            border-left: 1px solid #333;
        }
        .voucher-details thead tr th:last-child {
            border-right: 1px solid #333;
        }
        .voucher-details tbody tr td {
            /*border-bottom: 1px solid #333;*/
        }
        .voucher-details tbody tr td:first-child {
            border-left: 1px solid #333;
        }
        .voucher-details tbody tr td:last-child {
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
            min-width: 150px;
            max-width: 100%;
            height: auto;
        }
        .pt-1 {
            padding-top: 1rem;
        }
    </style>
</head>
<body>
    <script type="text/php">
      if (isset($pdf)) {
        $pdf->page_text(120, 740, "Para consultar el comprobante ingresar a {!! url('/') !!}/search", "Arial", 8, array(0, 0, 0));
        $font = $fontMetrics->getFont("Arial", "bold");
        $pdf->page_text(530, 740, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
      }
    </script>
<table class="voucher-company">
    <tr>
        <td width="25%">
            <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="company_logo">
        </td>
        <td width="45%">
            <table class="voucher-company-left">
                <tbody>
                <tr><td class="text-left font-xxlg font-bold">{{ $company->name }}</td></tr>
                <tr><td class="text-left font-xl font-bold">{{ 'RUC '.$company->number }}</td></tr>
                @if($establishment)
                    <tr><td class="text-left font-lg">{{ $establishment->getAddressFullAttribute() }}</td></tr>
                    <tr><td class="text-left font-lg">{{ ($establishment->email != '-')? $establishment->email : '' }}</td></tr>
                    <tr><td class="text-left font-lg font-bold">{{ ($establishment->phone != '-')? $establishment->phone : '' }}</td></tr>
                @endif
                </tbody>
            </table>
        </td>
        <td width="30%">
            <table class="voucher-company-right">
                <tbody>
                <tr><td class="text-center font-lg">{{ $document_type_description }}<br>E L E C T R Ó N I C A</td></tr>
                <tr><td class="text-center font-xlg font-bold">{{ $document_number }}</td></tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="voucher-information">
    <tr>
        <td width="55%">
            <table class="voucher-information-left">
                <tbody>
                    <tr>
                        <td width="50%">Fecha de emisión: </td>
                        <td width="50%">{{ $document->date_of_issue->format('d/m/Y') }}</td>
                    </tr>
                    @if($document_base->date_of_due)
                    <tr>
                        <td width="50%">Fecha de vencimiento: </td>
                        <td width="50%">{{ $document_base->date_of_due->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td width="20%">Cliente:</td>
                        <td width="80%">{{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <td width="20%">{{ $identity_document_type_description_array[$customer->identity_document_type->code] }}:</td>
                        <td width="80%">{{ $customer->number }}</td>
                    </tr>
                    @if ($customer->getAddressFullAttribute() !== '')
                        <tr>
                            <td width="20%">DIRECCIÓN:</td>
                            <td width="80%">{{ $customer->getAddressFullAttribute() }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </td>
        <td width="45%">
            <table class="voucher-information-right">
                <tbody>
                    @if ($optional->method_payment)
                    <tr>
                        <td width="50%">Condición de Pago: </td>
                        <td width="50%">{{ $optional->method_payment }}</td>
                    </tr>
                    @endif

                    @if ($optional->salesman)
                    <tr>
                        <td width="50%">Vendedor: </td>
                        <td width="50%">{{ $optional->salesman }}</td>
                    </tr>
                    @endif

                    @if ($optional->box_number)
                    <tr>
                        <td width="50%">N° Caja: </td>
                        <td width="50%">{{ $optional->box_number }}</td>
                    </tr>
                    @endif

                    @if ($document_base->purchase_order)
                    <tr>
                        <td width="50%">Orden de Compra: </td>
                        <td width="50%">{{ $document_base->purchase_order }}</td>
                    </tr>
                    @endif
                    @if ($document->guides)
                    @foreach($document->guides as $guide)
                        <tr>
                            <td>{{ \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('01', $guide->document_type_code)->description }}</td>
                            <td>{{ $guide->number }}</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>
@if($document->note)
    @php
        $document_affected = $document_base->affected_document_series.'-'.$document_base->affected_document_number;
        $noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$document_base->note_type_code);
    @endphp
    <table class="voucher-information top-note">
        <tr>
            <td>
                <table class="voucher-information-left">
                    <tbody>
                        <tr>
                            <td width="20%">Documento Afectado:</td>
                            <td width="20%">{{ $document_affected }}</td>
                            <td width="25%" class="text-right">Tipo de nota:</td>
                            <td width="35%">{{ $noteType->description}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table class="voucher-information-left">
                    <tbody>
                        <tr>
                            <td width="20%">Descripción:</td>
                            <td width="80%" class="text-left">{{ $document_base->description }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endif
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
            <td class="text-center">{{ $row->quantity }}</td>
            <td>{{ $row->unit_type_code }}</td>
            <td>
                {!! $row->item_description !!}
                @foreach($row->additional as $add)
                    <br/>{!! $add->name !!} : {{ $add->value }}
                @endforeach
            </td>
            <td  class="text-right" >{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($row->total, 2) }}</td>
        </tr>

    @endforeach
    </tbody>
    <tfoot style="border-top: 1px solid #333;">
        <tr>
            <td colspan="5" class="font-lg font-bold"  style="padding-top: 2rem;">Son: {{ $document->number_to_letter }} {{ $currency->description }}</td>
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
<table class="voucher-totals">
    <tbody>
    <tr>
        <td width="35%">
            <table class="voucher-totals-left">
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td class="text-left">{{$account->bank->description}} {{$account->currency_type->description}} {{$account->number}}</td>
                    </tr>
                @endforeach
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