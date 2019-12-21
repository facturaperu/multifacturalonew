<?php

namespace App\Imports;

use App\Models\Tenant\Document;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DocumentXmlImport 
{ 


    public static function input_transform($request){

        // dd($request);
        
        $document = $request['xml']['Invoice'];
        $is_item_array = $request['is_item_array'];
        $total = $document['cac:LegalMonetaryTotal']['cbc:PayableAmount']['_text'];
        
        $document_type_id = $document['cbc:InvoiceTypeCode']['_text'];
        $totals = $document['cac:TaxTotal'];
        $purchase_order =  isset($document['cac:OrderReference']['_text'])? $document['cac:OrderReference']['_text']:null;

        $full_number = explode('-',$document['cbc:ID']['_text']);

        $series = $full_number[0];
        $number = $full_number[1];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $hash = $document['ext:UBLExtensions']['ext:UBLExtension']['ext:ExtensionContent']['ds:Signature']['ds:SignedInfo']['ds:Reference']['ds:DigestValue']['_text'];
 
        $establishment = self::input_establishment($document);

        $customer = self::input_customer($document);

        if(in_array($document_type_id, ['01', '03'])) {

            $array_partial = self::input_invoice($document);
            $invoice = $array_partial['invoice'];
            $note = null;
        } 

        $doc_type = $array_partial['type'];
        $doc_group_id = $array_partial['group_id'];

        $ruc_company = $document['cac:Signature']['cac:SignatoryParty']['cac:PartyIdentification']['cbc:ID']['_text'];
        $filename = "{$ruc_company}-{$document_type_id}-{$document['cbc:ID']['_text']}";

        $record = [
            'type' => $doc_type,
            'group_id' => $doc_group_id,
            'hash' => $hash,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => auth()->user()->establishment_id,
            'establishment' => $establishment,
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $document['cbc:IssueDate']['_text'],
            'time_of_issue' => $document['cbc:IssueTime']['_text'],
            'customer_id' => $customer['id'],
            'customer' => $customer['data'],
            'currency_type_id' => $document['cbc:DocumentCurrencyCode']['_text'],
            'purchase_order' => $purchase_order,
            'quotation_id' => null,
            'exchange_rate_sale' => 1,
            'total_prepayment' => 0,
            'total_discount' => 0,
            'total_charge' => 0,
            'total_exportation' => 0,
            'total_free' => 0,
            'total_taxed' => $totals['cac:TaxSubtotal']['cbc:TaxableAmount']['_text'],
            'total_unaffected' => 0,
            'total_exonerated' => 0,
            'total_igv' => $totals['cac:TaxSubtotal']['cbc:TaxAmount']['_text'],
            'total_base_isc' => 0,
            'total_isc' => 0,
            'total_base_other_taxes' => 0,
            'total_other_taxes' => 0,
            'total_plastic_bag_taxes' => 0,
            'total_taxes' =>  $totals['cbc:TaxAmount']['_text'],
            'total_value' => $document['cac:LegalMonetaryTotal']['cbc:LineExtensionAmount']['_text'],
            'total' => $total,            
            'has_prepayment' => 0,
            'was_deducted_prepayment' => 0,
            'items' => self::input_items($document, $is_item_array),
            'charges' => [],
            'discounts' => [],
            'prepayments' => [],
            'guides' => [],
            'related' => [],
            'perception' => [],
            'detraction' => [],
            'invoice' => $invoice,
            'note' => $note,
            'additional_information' => null,
            'legends' => self::input_legends($document),
            'actions' => [
                'send_email' => false,
                'send_xml_signed' => false,
                'format_pdf' => "a4",
            ],
            'data_json' => null,
            'payments' => [],
            'send_server' => false,
        ];

        return $record;

    }
    
    private static function input_legends($inputs){
        
        $legends[] = [
            'code' => $inputs['cbc:Note']['_attributes']['languageLocaleID'],
            'value' => $inputs['cbc:Note']['_cdata']
        ];

        return $legends;
    }

    private static function input_customer($inputs)
    {

        $customer = $inputs['cac:AccountingCustomerParty'];
        $district_id = isset($customer['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:ID']['_text']) ? $customer['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:ID']['_text']:null;
        $identity_doc_type = $customer['cac:Party']['cac:PartyIdentification']['cbc:ID']['_attributes']['schemeID'];
        $number = $customer['cac:Party']['cac:PartyIdentification']['cbc:ID']['_text'];
        $name = $customer['cac:Party']['cac:PartyLegalEntity']['cbc:RegistrationName']['_cdata'];
        $province_id = ($district_id) ? substr($district_id, 0 ,4) : null;
        $department_id = ($district_id) ? substr($district_id, 0 ,2) : null;
        $address = isset($customer['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:AddressLine']['cbc:Line']['_cdata']) ? $customer['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:AddressLine']['cbc:Line']['_cdata']:null;
        $email = isset($customer['cac:Party']['cac:Contact']['cbc:ElectronicMail']['_text']) ? $customer['cac:Party']['cac:Contact']['cbc:ElectronicMail']['_text']:null;
        $telephone = isset($customer['cac:Party']['cac:Contact']['cbc:Telephone']['_text']) ? $customer['cac:Party']['cac:Contact']['cbc:Telephone']['_text']:null;
        
        $person = Person::whereType('customers')->where('number', $number)->first();

        if(!$person){
            
            $person = Person::create( [
                    'type' => 'customers',
                    'identity_document_type_id' => $identity_doc_type,
                    'number' => $number,
                    'name' => $name,
                    'trade_name' => null,
                    'country_id' => 'PE',
                    'department_id' => $department_id,
                    'province_id' => $province_id,
                    'district_id' => $district_id,
                    'address' => $address,
                    'email' => $email,
                    'telephone' => $telephone,
            ]);
        
        }


        
        return [
            'id' => $person->id,
            'data' => [
                'identity_document_type_id' => $identity_doc_type,
                'identity_document_type' => [
                    'id' => $identity_doc_type,
                    'description' => $person->identity_document_type->description,
                ],
                'number' => $number,
                'name' => $name,
                'trade_name' => $person->trade_name,
                'country_id' => $person->country_id,
                'country' => [
                    'id' => $person->country_id,
                    'description' => optional($person->country)->description,
                ],
                'department_id' => $department_id,
                'department' => [
                    'id' => $department_id,
                    'description' => optional($person->department)->description,
                ],
                'province_id' => $province_id,
                'province' => [
                    'id' => $province_id,
                    'description' => optional($person->province)->description,
                ],
                'district_id' => $district_id,
                'district' => [
                    'id' => $district_id,
                    'description' => optional($person->district)->description,
                ],
                'address' => $person->address,
                'email' => $person->email,
                'telephone' => $person->telephone,
            ]
        ];
    }
    

    private static function input_establishment($inputs)
    {
        $establishment = $inputs['cac:AccountingSupplierParty'];

        $district_id = $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:ID']['_text'];
        $province_id = ($district_id) ? substr($district_id, 0 ,4) : null;
        $department_id = ($district_id) ? substr($district_id, 0 ,2) : null;

        return [
            'country_id' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:Country']['cbc:IdentificationCode']['_text'],
            'country' => [
                'id' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:Country']['cbc:IdentificationCode']['_text'],
                'description' => 'PERU',
            ],
            'department_id' => $department_id,
            'department' => [
                'id' => $department_id,
                'description' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:CountrySubentity']['_text'],
            ],
            'province_id' => $province_id,
            'province' => [
                'id' => $province_id,
                'description' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:CityName']['_text'],
            ],
            'district_id' => $district_id,
            'district' => [
                'id' => $district_id,
                'description' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:District']['_text'],
            ],

            'urbanization' => null,
            'address' => isset($establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:AddressLine']['cbc:Line']['_cdata'])? $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cac:AddressLine']['cbc:Line']['_cdata']:null,
            'email' => isset($establishment['cac:Party']['cac:Contact']['cbc:ElectronicMail']['_text']) ? $establishment['cac:Party']['cac:Contact']['cbc:ElectronicMail']['_text']:null,
            'telephone' => isset($establishment['cac:Party']['cac:Contact']['cbc:Telephone']['_text'])? $establishment['cac:Party']['cac:Contact']['cbc:Telephone']['_text']:null,
            'code' => $establishment['cac:Party']['cac:PartyLegalEntity']['cac:RegistrationAddress']['cbc:AddressTypeCode']['_text'],
        ];
    }

    private static function input_invoice($inputs)
    {
        $operation_type_id = $inputs['cbc:InvoiceTypeCode']['_attributes']['listID'];
        $date_of_due = $inputs['cbc:DueDate']['_text'];
        $document_type_id = $inputs['cbc:InvoiceTypeCode']['_text'];

        return [
            'type' => 'invoice',
            'group_id' => ($document_type_id === '01')?'01':'02',
            'invoice' => [
                'operation_type_id' => $operation_type_id,
                'date_of_due' => $date_of_due,
            ]
        ];
    }
    
    private static function input_items($inputs, $is_item_array)
    {
        $items = [];

        if($is_item_array){

            foreach ($inputs['cac:InvoiceLine'] as $row) {
                

                $internal_id = isset($row['cac:Item']['cac:SellersItemIdentification']['cbc:ID']['_text']) ? $row['cac:Item']['cac:SellersItemIdentification']['cbc:ID']['_text']:null;
                $item_code = isset($row['cac:Item']['cac:CommodityClassification']['cbc:ItemClassificationCode']['_text']) ? $row['cac:Item']['cac:CommodityClassification']['cbc:ItemClassificationCode']['_text']:null;
                $description = $row['cac:Item']['cbc:Description']['_cdata'];
                $unit_type_id = $row['cbc:InvoicedQuantity']['_attributes']['unitCode'];
                $currency_type_id = $row['cac:TaxTotal']['cbc:TaxAmount']['_attributes']['currencyID'];
                $unit_price = $row['cac:PricingReference']['cac:AlternativeConditionPrice']['cbc:PriceAmount']['_text'];
                $sale_affectation_igv_type_id = $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:TaxExemptionReasonCode']['_text'];
                $item = null;

                if($internal_id){
                    $item = Item::where('internal_id', $internal_id)->first();
                }
        
                if(!$item){
                        
                    $item = Item::create([
                                'internal_id' => $internal_id,
                                'description' => $description,
                                'item_type_id' => '01',
                                'item_code' => $item_code,
                                'item_code_gs1' => null,
                                'unit_type_id' => $unit_type_id,
                                'currency_type_id' => $currency_type_id,
                                'sale_unit_price' =>  $unit_price,
                                'purchase_unit_price' =>  0,
                                'sale_affectation_igv_type_id' =>  $sale_affectation_igv_type_id,
                                'purchase_affectation_igv_type_id' =>  $sale_affectation_igv_type_id,
                                'has_isc' => 0,
                                'system_isc_type_id' => null,
                                'percentage_isc' => 0,
                                'suggested_price' => 0,
                                'calculate_quantity' => 0,
                                'has_igv' => 1,
                                'stock' => 0,
                                'stock_min' => 0,
                                'percentage_of_profit' => 0,
                                'amount_plastic_bag_taxes' => 0.1,
                            ]);

                }

                $items[] = [
                    'item_id' => $item->id,
                    'item' => [
                        'description' => $item->description,
                        'item_type_id' => $item->item_type_id,
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => null,
                        'unit_type_id' => $item->unit_type_id,
                        'presentation' => [],
                        'amount_plastic_bag_taxes' => $item->amount_plastic_bag_taxes,
                    ],

                    'quantity' => $row['cbc:InvoicedQuantity']['_text'],
                    'unit_value' => $row['cac:Price']['cbc:PriceAmount']['_text'],

                    'price_type_id' => $row['cac:PricingReference']['cac:AlternativeConditionPrice']['cbc:PriceTypeCode']['_text'],
                    'unit_price' => $unit_price,
                    
                    'affectation_igv_type_id' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:TaxExemptionReasonCode']['_text'],
                    'total_base_igv' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxableAmount']['_text'],

                    'percentage_igv' =>  $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:Percent']['_text'],
                    'total_igv' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxAmount']['_text'],
                    'system_isc_type_id' => null,
                    'total_base_isc' => 0,
                    'percentage_isc' => 0,
                    'total_isc' => 0,
                    'total_base_other_taxes' => 0,
                    'percentage_other_taxes' => 0,
                    'total_other_taxes' => 0,
                    'total_plastic_bag_taxes' => 0,
                    'total_taxes' => $row['cac:TaxTotal']['cbc:TaxAmount']['_text'],
                    'total_value' => $row['cbc:LineExtensionAmount']['_text'],
                    'total_charge' => 0,
                    'total_discount' => 0,
                    'total' => $row['cac:TaxTotal']['cbc:TaxAmount']['_text'] + $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxableAmount']['_text'],
                    'attributes' => null,
                    'discounts' => null,
                    'charges' => null,
                ];
            }

        }else{

            $row = $inputs['cac:InvoiceLine'];

            $internal_id = isset($row['cac:Item']['cac:SellersItemIdentification']['cbc:ID']['_text']) ? $row['cac:Item']['cac:SellersItemIdentification']['cbc:ID']['_text']:null;
            $item_code = isset($row['cac:Item']['cac:CommodityClassification']['cbc:ItemClassificationCode']['_text']) ? $row['cac:Item']['cac:CommodityClassification']['cbc:ItemClassificationCode']['_text']:null;
            $description = $row['cac:Item']['cbc:Description']['_cdata'];
            $unit_type_id = $row['cbc:InvoicedQuantity']['_attributes']['unitCode'];
            $currency_type_id = $row['cac:TaxTotal']['cbc:TaxAmount']['_attributes']['currencyID'];
            $unit_price = $row['cac:PricingReference']['cac:AlternativeConditionPrice']['cbc:PriceAmount']['_text'];
            $sale_affectation_igv_type_id = $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:TaxExemptionReasonCode']['_text'];
            $item = null;

            if($internal_id){
                $item = Item::where('internal_id', $internal_id)->first();
            }
    
            if(!$item){
                    
                $item = Item::create([
                            'internal_id' => $internal_id,
                            'description' => $description,
                            'item_type_id' => '01',
                            'item_code' => $item_code,
                            'item_code_gs1' => null,
                            'unit_type_id' => $unit_type_id,
                            'currency_type_id' => $currency_type_id,
                            'sale_unit_price' =>  $unit_price,
                            'purchase_unit_price' =>  0,
                            'sale_affectation_igv_type_id' =>  $sale_affectation_igv_type_id,
                            'purchase_affectation_igv_type_id' =>  $sale_affectation_igv_type_id,
                            'has_isc' => 0,
                            'system_isc_type_id' => null,
                            'percentage_isc' => 0,
                            'suggested_price' => 0,
                            'calculate_quantity' => 0,
                            'has_igv' => 1,
                            'stock' => 0,
                            'stock_min' => 0,
                            'percentage_of_profit' => 0,
                            'amount_plastic_bag_taxes' => 0.1,
                        ]);

            }

            $items[] = [
                'item_id' => $item->id,
                'item' => [
                    'description' => $item->description,
                    'item_type_id' => $item->item_type_id,
                    'internal_id' => $item->internal_id,
                    'item_code' => $item->item_code,
                    'item_code_gs1' => null,
                    'unit_type_id' => $item->unit_type_id,
                    'presentation' => [],
                    'amount_plastic_bag_taxes' => $item->amount_plastic_bag_taxes,
                ],

                'quantity' => $row['cbc:InvoicedQuantity']['_text'],
                'unit_value' => $row['cac:Price']['cbc:PriceAmount']['_text'],

                'price_type_id' => $row['cac:PricingReference']['cac:AlternativeConditionPrice']['cbc:PriceTypeCode']['_text'],
                'unit_price' => $unit_price,
                
                'affectation_igv_type_id' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:TaxExemptionReasonCode']['_text'],
                'total_base_igv' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxableAmount']['_text'],

                'percentage_igv' =>  $row['cac:TaxTotal']['cac:TaxSubtotal']['cac:TaxCategory']['cbc:Percent']['_text'],
                'total_igv' => $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxAmount']['_text'],
                'system_isc_type_id' => null,
                'total_base_isc' => 0,
                'percentage_isc' => 0,
                'total_isc' => 0,
                'total_base_other_taxes' => 0,
                'percentage_other_taxes' => 0,
                'total_other_taxes' => 0,
                'total_plastic_bag_taxes' => 0,
                'total_taxes' => $row['cac:TaxTotal']['cbc:TaxAmount']['_text'],
                'total_value' => $row['cbc:LineExtensionAmount']['_text'],
                'total_charge' => 0,
                'total_discount' => 0,
                'total' => $row['cac:TaxTotal']['cbc:TaxAmount']['_text'] + $row['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxableAmount']['_text'],
                'attributes' => null,
                'discounts' => null,
                'charges' => null,
            ];

        }
        

        return $items;
    }

}
