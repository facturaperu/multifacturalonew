@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $guides = $document->guides;
    $invoice = $document->invoice;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sac:AdditionalInformation>
                    @if($invoice->total_discount > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>2005</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_discount }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_exportation > 0)
                        <sac:AdditionalMonetaryTotal>
                            <cbc:ID>1000</cbc:ID>
                            <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exportation }}</cbc:PayableAmount>
                        </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_taxed > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1001</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_unaffected > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1002</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_unaffected }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_exonerated > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1003</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exonerated }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($invoice->total_free > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1004</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_free }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_value > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1005</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_value }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @foreach($legends as $legend)
                    <sac:AdditionalProperty>
                        <cbc:ID>{{ $legend->code }}</cbc:ID>
                        <cbc:Value>{{ $legend->description }}</cbc:Value>
                    </sac:AdditionalProperty>
                    @endforeach
                    @if($invoice->operation_type_code)
                    <sac:SUNATTransaction>
                        <cbc:ID>{{ $invoice->operation_type_code }}</cbc:ID>
                    </sac:SUNATTransaction>
                    @endif
                </sac:AdditionalInformation>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d')}}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->date_of_issue->format('H:i:s') }}</cbc:IssueTime>
    <cbc:InvoiceTypeCode>{{ $document->document_type_code }}</cbc:InvoiceTypeCode>
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    @if($invoice->date_of_due)
    <cbc:ExpiryDate>{{ $invoice->date_of_due->format('Y-m-d') }}</cbc:ExpiryDate>
    @endif
    @if($invoice->purchase_order)
    <cac:OrderReference>
        <cbc:ID>{{ $invoice->purchase_order }}</cbc:ID>
    </cac:OrderReference>
    @endif
    @foreach($guides as $guide)
        <cac:DespatchDocumentReference>
            <cbc:ID>{{ $guide->number }}</cbc:ID>
            <cbc:DocumentTypeCode>{{ $guide->document_type_code }}</cbc:DocumentTypeCode>
        </cac:DespatchDocumentReference>
    @endforeach
    <cac:Signature>
        <cbc:ID>{{ $company->number }}</cbc:ID>
        {{--<cbc:Note>CPE PERU Builder</cbc:Note>--}}
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#FACTURALO-PERU</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cbc:CustomerAssignedAccountID>{{ $company->number }}</cbc:CustomerAssignedAccountID>
        <cbc:AdditionalAccountID>{{ $company->identity_document_type->code }}</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PostalAddress>
                {{--<cbc:ID>{{ $establishment->location_code }}</cbc:ID>--}}
                {{--<cbc:StreetName><![CDATA[{{ $establishment->address }}]]></cbc:StreetName>--}}
                {{--<cbc:CityName><![CDATA[{{ $establishment->province }}]]></cbc:CityName>--}}
                {{--<cbc:CountrySubentity><![CDATA[{{ $establishment->department }}]]></cbc:CountrySubentity>--}}
                {{--<cbc:District><![CDATA[{{ $establishment->district }}]]></cbc:District>--}}
                {{--<cac:Country>--}}
                    {{--<cbc:IdentificationCode>{{ $establishment->country_code }}</cbc:IdentificationCode>--}}
                {{--</cac:Country>--}}
                <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
            </cac:PostalAddress>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cbc:CustomerAssignedAccountID>{{ $customer->number }}</cbc:CustomerAssignedAccountID>
        <cbc:AdditionalAccountID>{{ $customer->identity_document_type->code }}</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $customer->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>

    {{--{% if doc.anticipos -%}--}}
    {{--{% for ant in doc.anticipos -%}--}}
    {{--<cac:PrepaidPayment>--}}
        {{--<cbc:ID schemeID="{{ ant.tipoDocRel }}">{{ ant.nroDocRel }}</cbc:ID>--}}
        {{--<cbc:PaidAmount currencyID="{{ $document->currency_type_code }}">{{ ant.total|n_format }}</cbc:PaidAmount>--}}
        {{--<cbc:InstructionID schemeID="6">{{ emp.ruc }}</cbc:InstructionID>--}}
    {{--</cac:PrepaidPayment>--}}
    {{--{% endfor -%}--}}
    {{--{% endif -%}--}}
    @if($document->total_igv > 0)
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    @endif
    @if($document->total_other_taxes > 0)
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9999</cbc:ID>
                    <cbc:Name>OTROS</cbc:Name>
                    <cbc:TaxTypeCode>>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    @endif
    <cac:LegalMonetaryTotal>
        @if($invoice->total_global_discount > 0)
            <cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_global_discount }}</cbc:AllowanceTotalAmount>
        @endif
        @if($document->total_other_charges > 0)
            <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($invoice->total_prepayment > 0)
            <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_prepayment }}</cbc:PrepaidAmount>
        @endif
        {{--@if($document->total > 0)--}}
            <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
        {{--@endif --}}
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:InvoiceLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:InvoicedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{  $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        @if($row->total_discount > 0)
        <cac:AllowanceCharge>
            <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
            <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $row->total_discount }}</cbc:Amount>
        </cac:AllowanceCharge>
        @endif
        @if($row->total_igv > 0)
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
                <cac:TaxCategory>
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
            <cbc:Description><![CDATA[{{ $row->item_description }}]]></cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>{{ $row->item_id }}</cbc:ID>
            </cac:SellersItemIdentification>
            @if($row->item_code)
            <cac:StandardItemIdentification>
                <cbc:ID>{{ $row->item_code }}</cbc:ID>
            </cac:StandardItemIdentification>
            @endif
            @if($row->carriage_plate)
            <cac:AdditionalItemIdentification>
                <cbc:ID>{{ $row->carriage_plate }}</cbc:ID>
            </cac:AdditionalItemIdentification>
            @endif
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
        @if($row->first_housing_contract_number)
        <cac:DocumentReference>
            <cbc:ID>{{ $row->first_housing_contract_number }}</cbc:ID>
            <cbc:IssueDate>{{ $row->first_housing_credit_date }}</cbc:IssueDate>
        </cac:DocumentReference>
        @endif
    </cac:InvoiceLine>
    @endforeach
</Invoice>