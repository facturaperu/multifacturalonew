@php
    $establishment = $document->establishment;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<Retention xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:Retention-1"
           xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
           xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
           xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
           xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
           xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
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
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue->format('H:i:s') }}</cbc:IssueTime>
    <cac:AgentParty>
        <cac:PartyIdentification>
            <cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
        </cac:PartyName>
        <cac:PostalAddress>
            <cbc:ID>{{ $establishment->district_id }}</cbc:ID>
            <cbc:StreetName><![CDATA[{{ $establishment->address }}]]></cbc:StreetName>
            <cbc:CityName>{{ $establishment->department->description }}</cbc:CityName>
            <cbc:CountrySubentity>{{ $establishment->province->description }}</cbc:CountrySubentity>
            <cbc:District>{{ $establishment->district->description }}</cbc:District>
            <cac:Country>
                <cbc:IdentificationCode>{{ $establishment->country_id }}</cbc:IdentificationCode>
            </cac:Country>
        </cac:PostalAddress>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:AgentParty>
    <cac:ReceiverParty>
        <cac:PartyIdentification>
            <cbc:ID schemeID="{{ $document->supplier->document_type_id }}">{{ $document->supplier->number }}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[{{ $document->supplier->name }}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:ReceiverParty>
    <sac:SUNATRetentionSystemCode>{{ $document->retention_type_id }}</sac:SUNATRetentionSystemCode>
    <sac:SUNATRetentionPercent>{{ $document->percentage }}</sac:SUNATRetentionPercent>
    @if($document->observation)
        <cbc:Note><![CDATA[{{ $document->observation }}]]></cbc:Note>
    @endif
    <cbc:TotalInvoiceAmount currencyID="PEN">{{ $document->total }}</cbc:TotalInvoiceAmount>
    <sac:SUNATTotalPaid currencyID="PEN">{{ $document->total_payment }}</sac:SUNATTotalPaid>
    @foreach($document->details as $detail)
    @php($doc = $detail->document)
    <sac:SUNATRetentionDocumentReference>
        <cbc:ID schemeID="{{ $doc->document_type_id }}">{{ $doc->number_full }}</cbc:ID>
        <cbc:IssueDate>{{ $doc->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
        <cbc:TotalInvoiceAmount currencyID="{{ $doc->currency_type_id }}">{{ $doc->total }}</cbc:TotalInvoiceAmount>
        @if($doc->payments)
        @foreach($doc->payments as $payment)
        <cac:Payment>
            <cbc:ID>{{ $loop->iteration }}</cbc:ID>
            <cbc:PaidAmount currencyID="{{ $payment->currency_type_id }}">{{ $payment->total }}</cbc:PaidAmount>
            <cbc:PaidDate>{{ $payment->date_of_issue->format('Y-m-d') }}</cbc:PaidDate>
        </cac:Payment>
        @endforeach
        @endif
        @if($doc->amount && $doc->payment && $doc->date_of_retention)
        <sac:SUNATRetentionInformation>
            <sac:SUNATRetentionAmount currencyID="PEN">{{ $doc->amount }}</sac:SUNATRetentionAmount>
            <sac:SUNATRetentionDate>{{ $doc->date_of_retention->format('Y-m-d') }}</sac:SUNATRetentionDate>
            <sac:SUNATNetTotalPaid currencyID="PEN">{{ $doc->payment }}</sac:SUNATNetTotalPaid>
            @if($doc->exchange_rate)
            <cac:ExchangeRate>
                <cbc:SourceCurrencyCode>{{ $doc->exchange_rate->source_currency_type_id }}</cbc:SourceCurrencyCode>
                <cbc:TargetCurrencyCode>{{ $doc->exchange_rate->target_currency_type_id }}</cbc:TargetCurrencyCode>
                <cbc:CalculationRate>{{ $doc->exchange_rate->rate }}</cbc:CalculationRate>
                <cbc:Date>{{ $doc->exchange_rate->date->format('Y-m-d') }}</cbc:Date>
            </cac:ExchangeRate>
            @endif
        </sac:SUNATRetentionInformation>
        @endif
    </sac:SUNATRetentionDocumentReference>
    @endforeach
</Retention>