@php
    $invoice = $document->invoice;
    $establishment = $document->establishment;
    $customer = $document->customer;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    @if($invoice->date_of_due)
    <cbc:DueDate>{{ $invoice->date_of_due->format('Y-m-d') }}</cbc:DueDate>
    @endif
    <cbc:InvoiceTypeCode listID="{{ $invoice->operation_type_code }}">{{ $document->document_type_code }}</cbc:InvoiceTypeCode>
    @foreach($document->legends as $leg)
    <cbc:Note languageLocaleID="{{ $leg->code }}"><![CDATA[{{ $leg->value }}]]></cbc:Note>
    @endforeach
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    @if($document->purchase_order)
    <cac:OrderReference>
        <cbc:ID>{{ $document->purchase_order }}</cbc:ID>
    </cac:OrderReference>
    @endif
    @if($document->guides)
    @foreach($document->guides as $guide)
    <cac:DespatchDocumentReference>
        <cbc:ID>{{ $guide->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $guide->document_type_code }}</cbc:DocumentTypeCode>
    </cac:DespatchDocumentReference>
    @endforeach
    @endif
    @if($document->related)
    @foreach($document->related as $rel)
    <cac:AdditionalDocumentReference>
        <cbc:ID>{{ $rel->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $rel->document_type_code }}</cbc:DocumentTypeCode>
    </cac:AdditionalDocumentReference>
    @endforeach
    @endif
    @if($document->prepayments)
    @foreach($document->prepayments as $prepayment)
    <cac:AdditionalDocumentReference>
        <cbc:ID>{{ $prepayment->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $prepayment->document_type_code }}</cbc:DocumentTypeCode>
        <cbc:DocumentStatusCode>{{ $loop->iteration }}</cbc:DocumentStatusCode>
        <cac:IssuerParty>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
        </cac:IssuerParty>
    </cac:AdditionalDocumentReference>
    @endforeach
    @endif
    <cac:Signature>
        <cbc:ID>{{ $company->number }}</cbc:ID>
        <cbc:Note>{{ env('SIGNATURE_NOTE') }}</cbc:Note>
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
                <cbc:URI>{{ env('SIGNATURE_URI') }}</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:ID>{{ $establishment->district_code }}</cbc:ID>
                    <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
                    @if($establishment->urbanization)
                    <cbc:CitySubdivisionName>{{ $establishment->urbanization }}</cbc:CitySubdivisionName>
                    @endif
                    <cbc:CityName>{{ \App\Models\Tenant\Catalogs\Code::getDescriptionByCode($establishment->province_code) }}</cbc:CityName>
                    <cbc:CountrySubentity>{{ \App\Models\Tenant\Catalogs\Code::getDescriptionByCode($establishment->department_code) }}</cbc:CountrySubentity>
                    <cbc:District>{{ \App\Models\Tenant\Catalogs\Code::getDescriptionByCode($establishment->district_code) }}</cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $establishment->address }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{ $establishment->country_code }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
            @if($establishment->email || $establishment->telephone)
            <cac:Contact>
                @if($establishment->telephone)
                <cbc:Telephone>{{ $establishment->telephone }}</cbc:Telephone>
                @endif
                @if($establishment->email)
                <cbc:ElectronicMail>{{ $establishment->email }}</cbc:ElectronicMail>
                @endif
            </cac:Contact>
            @endif
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $customer->identity_document_type_code }}">{{ $customer->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $customer->name }}]]></cbc:RegistrationName>
                @if($customer->address)
                <cac:RegistrationAddress>
                    @if($customer->district_code)
                    <cbc:ID>{{ $customer->district_code }}</cbc:ID>
                    @endif
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $customer->address }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{ $customer->country_code }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
                @endif
            </cac:PartyLegalEntity>
            @if($customer->email || $customer->telephone)
            <cac:Contact>
                @if($customer->telephone)
                <cbc:Telephone>{{ $customer->telephone }}</cbc:Telephone>
                @endif
                @if($customer->email)
                <cbc:ElectronicMail>{{ $customer->email }}</cbc:ElectronicMail>
                @endif
            </cac:Contact>
            @endif
        </cac:Party>
    </cac:AccountingCustomerParty>
    @if($document->detraction)
    @php($detraction = $document->detraction)
    <cac:PaymentMeans>
        <cbc:PaymentMeansCode>{{ $detraction->payment_method_code }}</cbc:PaymentMeansCode>
        <cac:PayeeFinancialAccount>
            <cbc:ID>{{ $detraction->bank_account }}</cbc:ID>
        </cac:PayeeFinancialAccount>
    </cac:PaymentMeans>
    <cac:PaymentTerms>
        <cbc:PaymentMeansID>{{ $detraction->detraction_type_code }}</cbc:PaymentMeansID>
        <cbc:PaymentPercent>{{ $detraction->percentage }}</cbc:PaymentPercent>
        <cbc:Amount currencyID="PEN">{{ $detraction->amount }}</cbc:Amount>
    </cac:PaymentTerms>
    @endif
    @if($document->perception)
    @php($perception = $document->perception)
    <cac:PaymentTerms>
        <cbc:ID>Percepcion</cbc:ID>
        <cbc:Amount currencyID="PEN">{{ $perception->amount }}</cbc:Amount>
    </cac:PaymentTerms>
    @endif
    @if($document->prepayments)
    @foreach($document->prepayments as $prepayment)
    <cac:PrepaidPayment>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:PaidAmount currencyID="{{ $prepayment->currency_type_code }}">{{ $prepayment->amount }}</cbc:PaidAmount>
    </cac:PrepaidPayment>
    @endforeach
    @endif
    @if($document->charges)
    @foreach($document->charges as $charge)
    <cac:AllowanceCharge>
        <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>{{ $charge->code }}</cbc:AllowanceChargeReasonCode>
        <cbc:MultiplierFactorNumeric>{{ $charge->factor }}</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $charge->amount }}</cbc:Amount>
        <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $charge->base }}</cbc:BaseAmount>
    </cac:AllowanceCharge>
    @endforeach
    @endif
    @if($document->discounts)
    @foreach($document->discounts as $discount)
    <cac:AllowanceCharge>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>{{ $discount->code }}</cbc:AllowanceChargeReasonCode>
        <cbc:MultiplierFactorNumeric>{{ $discount->factor }}</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $discount->amount }}</cbc:Amount>
        <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $discount->base }}</cbc:BaseAmount>
    </cac:AllowanceCharge>
    @endforeach
    @endif
    @if($document->perception)
    @php($perception = $document->perception)
    <cac:AllowanceCharge>
        <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>{{ $perception->code }}</cbc:AllowanceChargeReasonCode>
        <cbc:MultiplierFactorNumeric>{{ $perception->percentage }}</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="PEN">{{ $perception->amount }}</cbc:Amount>
        <cbc:BaseAmount currencyID="PEN">{{ $perception->base }}</cbc:BaseAmount>
    </cac:AllowanceCharge>
    @endif
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxes }}</cbc:TaxAmount>
        @if($document->total_isc > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_base_isc }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_isc }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>2000</cbc:ID>
                    <cbc:Name>ISC</cbc:Name>
                    <cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_taxed > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_unaffected > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_unaffected }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9998</cbc:ID>
                    <cbc:Name>INA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_exonerated > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exonerated }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9997</cbc:ID>
                    <cbc:Name>EXO</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_free > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_free }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9996</cbc:ID>
                    <cbc:Name>GRA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_exportation > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exportation }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9995</cbc:ID>
                    <cbc:Name>EXP</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_other_taxes > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_base_other_taxes }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9999</cbc:ID>
                    <cbc:Name>OTROS</cbc:Name>
                    <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_value }}</cbc:LineExtensionAmount>
        @if($document->total_discount > 0)
        <cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_discount }}</cbc:AllowanceTotalAmount>
        @endif
        @if($document->total_charges > 0)
        <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($document->total_prepayment > 0)
        <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_prepayment }}</cbc:PrepaidAmount>
        @endif
        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    @foreach($document->details as $detail)
    <cac:InvoiceLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:InvoicedQuantity unitCode="{{ $detail->item->unit_type_code }}">{{ $detail->quantity }}</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $detail->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        @if($detail->charges)
        @foreach($detail->charges as $charge)
        <cac:AllowanceCharge>
            <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReasonCode>{{ $charge->code }}</cbc:AllowanceChargeReasonCode>
            <cbc:MultiplierFactorNumeric>{{ $charge->factor }}</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $charge->amount }}</cbc:Amount>
            <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $charge->base }}</cbc:BaseAmount>
        </cac:AllowanceCharge>
        @endforeach
        @endif
        @if($detail->discounts)
        @foreach($detail->discounts as $discount)
        <cac:AllowanceCharge>
            <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReasonCode>{{ $discount->code }}</cbc:AllowanceChargeReasonCode>
            <cbc:MultiplierFactorNumeric>{{ $discount->factor }}</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $discount->amount }}</cbc:Amount>
            <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $discount->base }}</cbc:BaseAmount>
        </cac:AllowanceCharge>
        @endforeach
        @endif
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_taxes }}</cbc:TaxAmount>
            @if($detail->total_isc > 0)
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_base_isc }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_isc }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $detail->percentage_isc }}</cbc:Percent>
                    <cbc:TierRange>{{ $detail->system_isc_type_code }}</cbc:TierRange>
                    <cac:TaxScheme>
                        <cbc:ID>2000</cbc:ID>
                        <cbc:Name>ISC</cbc:Name>
                        <cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
            @endif
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_base_igv }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_igv }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $detail->percentage_igv }}</cbc:Percent>
                    <cbc:TaxExemptionReasonCode>{{ $detail->affectation_igv_type_code }}</cbc:TaxExemptionReasonCode>
                    @php($affectation = \App\CoreFacturalo\Templates\FunctionTribute::getByAffectation($detail->affectation_igv_type_code))
                    <cac:TaxScheme>
                        <cbc:ID>{{ $affectation['id'] }}</cbc:ID>
                        <cbc:Name>{{ $affectation['name'] }}</cbc:Name>
                        <cbc:TaxTypeCode>{{ $affectation['code'] }}</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
            @if($detail->total_other_taxes > 0)
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_base_other_taxes }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_other_taxes }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $detail->percentage_other_taxes }}</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>9999</cbc:ID>
                        <cbc:Name>OTROS</cbc:Name>
                        <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
            @endif
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description><![CDATA[{{ $detail->item_description }}]]></cbc:Description>
            @if($detail->item->internal_id)
            <cac:SellersItemIdentification>
                <cbc:ID>{{ $detail->item->internal_id }}</cbc:ID>
            </cac:SellersItemIdentification>
            @endif
            @if($detail->item->item_code)
            <cac:CommodityClassification>
                <cbc:ItemClassificationCode>{{ $detail->item->item_code }}</cbc:ItemClassificationCode>
            </cac:CommodityClassification>
            @endif
            @if($detail->item->item_code_gs1)
            <cac:StandardItemIdentification>
                <cbc:ID>{{ $detail->item->item_code_gs1 }}</cbc:ID>
            </cac:StandardItemIdentification>
            @endif
            @if($detail->attributes)
            @foreach($detail->attributes as $attr)
            <cac:AdditionalItcompanyroperty >
                <cbc:Name>{{ $attr->name }}</cbc:Name>
                <cbc:NameCode>{{ $attr->code }}</cbc:NameCode>
                @if($attr->value)
                <cbc:Value>{{ $attr->value }}</cbc:Value>
                @endif
                @if($attr->start_date || $attr->end_date || $attr->duration)
                <cac:UsabilityPeriod>
                    @if($attr->start_date)
                    <cbc:StartDate>{{ $attr->start_date }}</cbc:StartDate>
                    @endif
                    @if($attr->end_date)
                    <cbc:EndDate>{{ $attr->end_date }}</cbc:EndDate>
                    @endif
                    @if($attr->duration)
                    <cbc:DurationMeasure unitCode="DAY">{{ $attr->duration }}</cbc:DurationMeasure>
                    @endif
                </cac:UsabilityPeriod>
                @endif
            </cac:AdditionalItcompanyroperty>
            @endforeach
            @endif
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
    @endforeach
</Invoice>