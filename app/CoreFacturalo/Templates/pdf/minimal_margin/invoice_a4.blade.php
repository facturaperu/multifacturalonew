@php
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
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
        @endif
        <td width="50%" class="pl-3">
            <div class="text-left">
                <h4 class="font-bold">{{ $company->name }}</h4>
                <h6>
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                </h6>
                <h6>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h6>
                <h6>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h6>
            </div>
        </td>
        <td width="30%" class="border-box text-center">
            <h5 class="text-center font-bold">{{ $document->document_type->description }}</h5>
            <h5>{{ 'RUC '.$company->number }}</h5>
            <h5 class="text-center font-bold">N° {{ $document_number }}</h5>
        </td>
    </tr>
</table>
<table class="full-width mt-5 border-box">
    <tr>
        <td width="60%"><span class="font-bold">SEÑOR(ES):</span> {{ $customer->name }}</td>
        <td width="40%"><span class="font-bold">VENDEDOR:</span> </td>
    </tr>
    <tr>
        <td><span class="font-bold">RUC:</span> {{$customer->number}}</td>
        <td><span class="font-bold">FECHA DE EMISIÓN:</span> {{$document->date_of_issue->format('Y-m-d')}}</td>
    </tr>
    <tr>
        <td><span class="font-bold">DIRECCIÓN:</span> {{ $customer->address }}</td>
        <td valign="top">
            @if($invoice)
                <span class="font-bold">FECHA DE VENCIMIENTO:</span> {{$invoice->date_of_due->format('Y-m-d')}}
            @endif
        </td>
    </tr>
    <tr>
        <td><span class="font-bold">PROVINCIA:</span> {{ $customer->province->description }}</td>
        <td><span class="font-bold">COND. DE VENTA:</span> </td>
    </tr>
    <tr>
        <td><span class="font-bold">DISTRITO:</span> {{ $customer->district->description }}</td>
        <td></td>
    </tr>
</table>


{{--<table class="full-width mt-3">--}}
    {{--@if ($document->purchase_order)--}}
        {{--<tr>--}}
            {{--<td width="25%">Orden de Compra: </td>--}}
            {{--<td>:</td>--}}
            {{--<td class="text-left">{{ $document->purchase_order }}</td>--}}
        {{--</tr>--}}
    {{--@endif--}}
    {{--@if ($document->quotation_id)--}}
        {{--<tr>--}}
            {{--<td width="15%">Cotización:</td>--}}
            {{--<td class="text-left" width="85%">{{ $document->quotation->identifier }}</td>--}}
        {{--</tr>--}}
    {{--@endif--}}
{{--</table>--}}

@if ($document->guides)
<br/>
{{--<strong>Guías:</strong>--}}
<table>
    @foreach($document->guides as $guide)
        <tr>
            @if(isset($guide->document_type_description))
            <td>{{ $guide->document_type_description }}</td>
            @else
            <td>{{ $guide->document_type_id }}</td>
            @endif
            <td>:</td>
            <td>{{ $guide->number }}</td>
        </tr>
    @endforeach
</table>
@endif

<table class="full-width mt-3">
    {{-- @if ($document->purchase_order)
        <tr>
            <td width="120px">ORDEN DE COMPRA</td>
            <td width="8px">:</td>
            <td>{{ $document->purchase_order }}</td>
        </tr>
    @endif --}}
    @if ($document->quotation_id)
        <tr>
            <td>COTIZACIÓN</td>
            <td>:</td>
            <td>{{ $document->quotation->identifier }}</td>
        </tr>
    @endif
    @if(!is_null($document_base))
    <tr>
        <td width="120px">DOC. AFECTADO</td>
        <td width="8px">:</td>
        <td>{{ $affected_document_number }}</td>
    </tr>
    <tr>
        <td>TIPO DE NOTA</td>
        <td>:</td>
        <td>{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>
    </tr>
    <tr>
        <td>DESCRIPCIÓN</td>
        <td>:</td>
        <td>{{ $document_base->note_description }}</td>
    </tr>
    @endif
</table>

{{--<table class="full-width mt-3">--}}
    {{--<tr>--}}
        {{--<td width="25%">Documento Afectado:</td>--}}
        {{--<td width="20%">{{ $document_base->affected_document->series }}-{{ $document_base->affected_document->number }}</td>--}}
        {{--<td width="15%">Tipo de nota:</td>--}}
        {{--<td width="40%">{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="align-top">Descripción:</td>--}}
        {{--<td class="text-left" colspan="3">{{ $document_base->note_description }}</td>--}}
    {{--</tr>--}}
{{--</table>--}}

<table class="full-width mt-10 mb-10" style="min-height: 300px !important;">
    <thead class="">
    <tr class="bg-grey">
        <th class="border-top-bottom text-center py-2" width="10%">CANTIDAD</th>
        <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-right py-2" width="12%">VALOR U.</th>
        <th class="border-top-bottom text-right py-2" width="12%">DESC. %</th>
        <th class="border-top-bottom text-right py-2" width="12%">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-left align-top">
                {!!$row->item->description!!} @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        @if($attr->description != 'Número de Placa')
                            <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                        @endif
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
                    @endforeach
                @endif
            </td>
            <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right align-top">
                @if($row->discounts)
                    @php
                        $total_discount_line = 0;
                        foreach ($row->discounts as $disto) {
                            $total_discount_line = $total_discount_line + $disto->amount;
                        }
                    @endphp
                    {{ number_format($total_discount_line, 2) }}
                @else
                0
                @endif
            </td>
            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- <table class="full-width">
    <tr>
        @if(in_array($document->document_type->id,['01','03']))
            <td width="30%" style="text-align: top; vertical-align: top;" class="p-3 border-box">
                <div class="">
                    <p><span class="font-bold">BANCO DE CREDITO DEL PERU</span></p>
                    @foreach($accounts as $account)
                        @if($account->bank->description == 'BANCO DE CREDITO DEL PERU')
                            <p>{{$account->currency_type->description}}</p>
                            <p>{{$account->number}}</p>
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
    <td width="30%"></td>
    <td width="30%" class="text-center">
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
</table> --}}


</body>
</html>
