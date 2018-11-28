@php
    // $establishment = $document->establishment;
    // $customer = $document->customer;
    // $details = $document->details;
    // $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    // $document_type_description_array = [
    //     '01' => 'FACTURA',
    //     '03' => 'BOLETA DE VENTA',
    //     '07' => 'NOTA DE CREDITO',
    //     '08' => 'NOTA DE DEBITO',
    // ];
    // $identity_document_type_description_array = [
    //     '1' => 'DNI',
    //     '6' => 'RUC',
    // ];
    // $document_type_description = $document_type_description_array[$document->document_type_code];

    // $idDocument = $document->id;
    // if($note = \App\Models\Tenant\Note::where('document_id', $idDocument)->first()){
    //     $document_affected = $note->affected_document_series.'-'.$note->affected_document_number;

    //     $noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$note->note_type_code);
    // }
@endphp
<html>
<head>
    <title></title>
    <style type="text/css">
        @page { size: 8cm 20cm; }
        body {
            font-size: 10px;
            font-family: sans-serif;
        }
        .w-100 {
            width: 100%
        }
        .w-50 {
            width: 50%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .py-2 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>
</head>
<body>
    {{-- <script type="text/php">
      if (isset($pdf)) {
        $pdf->page_text(150, 60, "Para consultar el comprobante ingresar a {!! url('/') !!}/search", "Arial", 8, array(0, 0, 0));
      }
    </script> --}}
    <div class="logo">
        <img src="{{-- {{ asset('storage/uploads/logos/'.$company->logo) }} --}}" class="company_logo">
    </div>
    <div class="text-center py-2">
        <p class="text-uppercase">Nombre Comercial <br>
        Nombre <br>
        RUC: 12312312312 {{-- {{ 'RUC '.$company->number }} --}}<br>
        dirección{{-- {{ $establishment->getAddressFullAttribute() }} --}}</p>
        <p>{{-- {{ $document_type_description }} --}} <br> E L E C T R Ó N I C A</p>
    </div>
    <table class="w-100" style="border-bottom: 1px solid #bbb;">
        <tbody>
            <tr class="py-2">
                <td width="20%">
                    qwe
                </td>
                <td width="80%" class="text-right">
                    qwe
                </td>
            </tr>
            <tr>
                <td>Cliente</td>
                <td>Raul{{-- {{ $customer->name }} --}}</td>
            </tr>
            <tr>
                <td>DNI{{-- {{ $identity_document_type_description_array[$customer->identity_document_type->code] }}: --}}</td>
                <td>12346578{{-- {{ $customer->name }} --}}</td>
            </tr>
        </tbody>
    </table>
    {{-- @if($document_affected) --}}
        <table class="">
            <tbody>
                <tr>
                    <td style="vertical-align: top;" width="20%">Documento Afectado:</td>
                    <td style="vertical-align: top;"width="80%">F001-654689{{-- {{$document_affected}} --}}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" >Tipo de nota:</td>
                    <td style="vertical-align: top;">anulacion equis{{-- {{$noteType->description}} --}}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Descripción:</td>
                    <td >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua.{{-- {{$note->description}} --}}</td>
                </tr>
            </tbody>
        </table>
    {{-- @endif --}}
    <table class="w-100">
        <thead style="border-top: 1px solid #bbb; border-bottom: 1px solid #bbb;">
            <tr>
                <td style="padding: 5px 0 5px" class="text-center" width="">CANT.</td>
                <td style="padding: 5px 0 5px" width="">U.M.</td>
                <td style="padding: 5px 0 5px" width="">DESCRIPCIÓN</td>
                <td style="padding: 5px 0 5px" class="text-right" width="">P.UNIT</td>
                <td style="padding: 5px 0 5px" class="text-right" width="">TOTAL</td>
            </tr>
        </thead>
        <tbody style="border-bottom: 1px solid #bbb;">
        {{-- @foreach($details as $row) --}}
            <tr>
                <td class="text-center">1.000{{-- {!! $row->quantity !!} --}}</td>
                <td >NIU{{-- {!! $row->unit_type_code !!} --}}</td>
                <td >Chicharron{{-- {!! $row->item_description !!} --}}</td>
                <td  class="text-right" >15.50{{-- {{ number_format($row->unit_value, 2) } --}}</td>
                <td class="text-right">12.30{{-- {{ number_format($row->total, 2) }} --}}</td>
            </tr>
        </tbody>
    </table>
    <table class="w-100">
        <tbody style="border-bottom: 1px solid #bbb;">
        {{-- @endforeach --}}
            {{-- @if($document->total_exonerated > 0) --}}
                <tr>
                    <td width="80%">Operaciones Exoneradas: {{-- {{ $document->currency_type_code }} --}}</td>
                    <td width="20%" class="text-right ">0.00{{-- {{ number_format($document->total_exonerated, 2) }} --}}</td>
                </tr>
            {{-- @endif --}}
            {{-- @if($document->total_taxed > 0) --}}
                <tr>
                    <td >Operaciones Gravadas: {{-- {{ $document->currency_type_code }} --}}</td>
                    <td class="text-right ">{{-- {{ number_format($document->total_taxed, 2) }} --}}</td>
                </tr>
            {{-- @endif --}}
            <tr>
                <td >IGV: {{-- {{ $document->currency_type_code }} --}}</td>
                <td class="text-right ">{{-- {{ number_format($document->total_igv, 2) }} --}}</td>
            </tr>
            <tr>
                <td >IMPORTE TOTAL: {{-- {{ $document->currency_type_code }} --}}</td>
                <td class="text-right">{{-- {{ number_format($document->total, 2) }} --}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="font-lg font-bold"  style="padding-top: 2rem;">Son: {{-- {{ $document->number_to_letter }} {{ $document->currency_type_code }} --}}</td>
            </tr>
        </tfoot>
    </table>
    <div class="text-center">
        {{-- {{ $document->hash }} --}}
        <p>Código Hash</p>
    </div>
</body>
</html>