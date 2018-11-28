@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $note = $document->note;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
            xmlns:ccts="urn:un:unece:uncefact:documentation:2"
            xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
            xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
            xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
            xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
            xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>{{$document->series.'-'.$document->number}}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    @foreach($legends as $legend)
        <cbc:Note languageLocaleID="{{ $legend->code }}">{{ $legend->description }}</cbc:Note>
    @endforeach
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ReferenceID>
        <cbc:ResponseCode>{{ $note->note_type_code }}</cbc:ResponseCode>
        <cbc:Description>{{ $note->description }}</cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ID>
            <cbc:DocumentTypeCode>{{ $note->affected_document_type_code }}</cbc:DocumentTypeCode>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    <cac:Signature>
        <cbc:ID>{{ $company->number }}</cbc:ID>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->name }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#FACTURALO-PERU</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $company->identity_document_type->code }}"
                        schemeName="SUNAT:Identificador de Documento de Identidad"
                        schemeAgencyName="PE:SUNAT"
                        schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    {{----}}
    {{--<cac:AccountingSupplierParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyName>--}}
                {{--<cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>--}}
            {{--</cac:PartyName>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="{{ $company->identity_document_type_code }}"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $company->number }}</cbc:CompanyID>--}}
                {{--<cac:RegistrationAddress>--}}
                    {{--<cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>--}}
                {{--</cac:RegistrationAddress>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>-</cbc:ID>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingSupplierParty>--}}
    {{--<cac:AccountingCustomerParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName>{{ $customer->name }}</cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="{{ $customer->identity_document_type_code }}"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $customer->number }}</cbc:CompanyID>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>-</cbc:ID>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingCustomerParty>--}}
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $customer->identity_document_type->code }}"
                        schemeName="SUNAT:Identificador de Documento de Identidad"
                        schemeAgencyName="PE:SUNAT"
                        schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $customer->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>{{ $customer->name }}</cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    @if($document->total_igv > 0)
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    @endif
    <cac:LegalMonetaryTotal>
        @if($note->total_global_discount > 0)
        <cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $note->total_global_discount }}</cbc:AllowanceTotalAmount>
        @endif
        @if($document->total_other_charges > 0)
        <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($note->total_prepayment > 0)
        <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $note->total_prepayment }}</cbc:PrepaidAmount>
        @endif
        @if($document->total > 0)
        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
        @endif
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:CreditNoteLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:CreditedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:CreditedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        @if($row->total_igv > 0)
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $row->percentage_igv }}</cbc:Percent>
                    <cbc:TaxExemptionReasonCode>{{ $row->affectation_igv_type_code }}</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        @endif
        <cac:Item>
            <cbc:Description>{{ $row->item_description }}</cbc:Description>
            @if($row->internal_id)
            <cac:SellersItemIdentification>
                <cbc:ID>{{ $row->internal_id }}</cbc:ID>
            </cac:SellersItemIdentification>
            @endif
            @foreach($row->additional as $other)
                <cac:AdditionalItemProperty>
                    <cbc:Name><![CDATA[{{ $other->name }}]]></cbc:Name>
                    <cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem"
                                  listAgencyName="PE:SUNAT">{{ $other->code }}</cbc:NameCode>
                    <cbc:Value>{{ $other->value }}</cbc:Value>
                    @if($other->start_date || $other->end_date || $other->duration)
                        <cac:UsabilityPeriod>
                            @if($other->start_date)
                                <cbc:StartDate>{{ $other->start_date }}</cbc:StartDate>
                            @endif
                            @if($other->end_date)
                                <cbc:EndDate>{{ $other->end_date }}</cbc:EndDate>
                            @endif
                            @if($other->duration)
                                <cbc:DurationMeasure unitCode="DAY">{{ $other->duration }}</cbc:DurationMeasure>
                            @endif
                        </cac:UsabilityPeriod>
                    @endif
                </cac:AdditionalItemProperty>
            @endforeach
            @if($row->item_code)
            <cac:CommodityClassification>
                <cbc:ItemClassificationCode listID="UNSPSC"
                                            listAgencyName="GS1 US"
                                            listName="Item Classification">{{ $row->item_code }}</cbc:ItemClassificationCode>
            </cac:CommodityClassification>
            @endif
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:CreditNoteLine>
    @endforeach
</CreditNote>