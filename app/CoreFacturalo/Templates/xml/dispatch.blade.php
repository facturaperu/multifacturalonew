{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2"
                xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    <cbc:DespatchAdviceTypeCode>{{ $document->document_type_id }}</cbc:DespatchAdviceTypeCode>
    @if($document->observations)
    <cbc:Note><![CDATA[{{ $document->observations }}]]></cbc:Note>
    @endif
    {{--{% if doc.docBaja -%}--}}
    {{--<cac:OrderReference>--}}
        {{--<cbc:ID>{{ doc.docBaja.nroDoc }}</cbc:ID>--}}
        {{--<cbc:OrderTypeCode listAgencyName="PE:SUNAT"--}}
                           {{--listName="SUNAT:Identificador de Tipo de Documento"--}}
                           {{--listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">{{ doc.docBaja.tipoDoc }}</cbc:OrderTypeCode>--}}
    {{--</cac:OrderReference>--}}
    {{--{% endif -%}--}}
    @if($document->related)
    <cac:AdditionalDocumentReference>
        <cbc:ID>{{ $document->related->number }}</cbc:ID>
        <cbc:DocumentTypeCode listAgencyName="PE:SUNAT"
                              listName="SUNAT:Identificador de documento relacionado"
                              listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo21">{{ $document->related->document_type_id }}</cbc:DocumentTypeCode>
    </cac:AdditionalDocumentReference>
    @endif
    <cac:DespatchSupplierParty>
        <cbc:CustomerAssignedAccountID schemeID="6">{{ $company->number }}</cbc:CustomerAssignedAccountID>
        <cac:Party>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:DespatchSupplierParty>
    <cac:DeliveryCustomerParty>
        <cbc:CustomerAssignedAccountID schemeID="{{ $document->customer->identity_document_id }}">{{ $document->customer->number }}</cbc:CustomerAssignedAccountID>
        <cac:Party>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $document->customer->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:DeliveryCustomerParty>
    {{--{% if doc.tercero -%}--}}
    {{--<cac:SellerSupplierParty>--}}
        {{--<cbc:CustomerAssignedAccountID schemeID="{{ doc.tercero.tipoDoc }}">{{ doc.tercero.numDoc }}</cbc:CustomerAssignedAccountID>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyLegalEntity>--}}
                {{--<cbc:RegistrationName><![CDATA[{{ doc.tercero.rznSocial|raw }}]]></cbc:RegistrationName>--}}
            {{--</cac:PartyLegalEntity>--}}
        {{--</cac:Party>--}}
    {{--</cac:SellerSupplierParty>--}}
    {{--{% endif -%}--}}
    @php($shipment = $document->shipment)
    <cac:Shipment>
        <cbc:ID>1</cbc:ID>
        <cbc:HandlingCode>{{ $shipment->transfer_code }}</cbc:HandlingCode>
        @if($shipment->transfer_description)
        <cbc:Information>{{ $shipment->transfer_description }}</cbc:Information>
        @endif
        <cbc:GrossWeightMeasure unitCode="{{ $shipment->weight_unit_type_id }}">{{ $shipment->total_weight }}</cbc:GrossWeightMeasure>
        @if($shipment->packages_number)
        <cbc:TotalTransportHandlingUnitQuantity>{{ $shipment->packages_number }}</cbc:TotalTransportHandlingUnitQuantity>
        @endif
        <cbc:SplitConsignmentIndicator>{{ ($shipment->transfer)?'true':'false' }}</cbc:SplitConsignmentIndicator>
        <cac:ShipmentStage>
            <cbc:TransportModeCode>{{ $shipment->modTraslado }}</cbc:TransportModeCode>
            <cac:TransitPeriod>
                <cbc:StartDate>{{ $shipment->transfer_date }}</cbc:StartDate>
            </cac:TransitPeriod>
            @if($shipment->dispatcher)
            @php($dispatcher = $shipment->dispatcher)
            <cac:CarrierParty>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="{{ $dispatcher->identity_document_type_id }}">{{ $dispatcher->number }}</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA[{{ $dispatcher->number }}]]></cbc:Name>
                </cac:PartyName>
            </cac:CarrierParty>
            <cac:TransportMeans>
                <cac:RoadTransport>
                    <cbc:LicensePlateID>{{ $dispatcher->license_plate }}</cbc:LicensePlateID>
                </cac:RoadTransport>
            </cac:TransportMeans>
            <cac:DriverPerson>
                <cbc:ID schemeID="{{ $dispatcher->driver->identity_document_type_id }}">{{ $dispatcher->driver->number }}</cbc:ID>
            </cac:DriverPerson>
            @endif
        </cac:ShipmentStage>
        <cac:Delivery>
            <cac:DeliveryAddress>
                <cbc:ID>{{ $shipment->delivery->location_id }}</cbc:ID>
                <cbc:StreetName>{{ $shipment->delivery->address }}</cbc:StreetName>
            </cac:DeliveryAddress>
        </cac:Delivery>
        @if($shipment->container_number)
        <cac:TransportHandlingUnit>
            <cbc:ID>{{ $shipment->container_number }}</cbc:ID>
        </cac:TransportHandlingUnit>
        @endif
        <cac:OriginAddress>
            <cbc:ID>{{ $shipment->origen->location_id }}</cbc:ID>
            <cbc:StreetName>{{ $shipment->origen->address }}</cbc:StreetName>
        </cac:OriginAddress>
        @if($shipment->port_code)
        <cac:FirstArrivalPortLocation>
            <cbc:ID>{{ $shipment->port_code }}</cbc:ID>
        </cac:FirstArrivalPortLocation>
        @endif
    </cac:Shipment>
    @foreach($document->details as $detail)
    <cac:DespatchLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:DeliveredQuantity unitCode="{{ $detail->item->unit_type_id }}">{{ $detail->quantity }}</cbc:DeliveredQuantity>
        <cac:OrderLineReference>
            <cbc:LineID>{{ $loop->iteration }}</cbc:LineID>
        </cac:OrderLineReference>
        <cac:Item>
            <cbc:Name><![CDATA[{{ $detail->item_description }}]]></cbc:Name>
            <cac:SellersItemIdentification>
                <cbc:ID>{{ $detail->item->internal_id }}</cbc:ID>
            </cac:SellersItemIdentification>
            @if($detail->item->item_code)
            <cac:CommodityClassification>
                <cbc:ItemClassificationCode listID="UNSPSC"
                                            listAgencyName="GS1 US"
                                            listName="Item Classification">{{ $detail->item->item_code }}</cbc:ItemClassificationCode>
            </cac:CommodityClassification>
            @endif
        </cac:Item>
    </cac:DespatchLine>
    @endforeach
</DespatchAdvice>