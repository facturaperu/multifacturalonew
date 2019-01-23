<?php

namespace App\CoreFacturalo\Inputs\Documents;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Inputs\Common\LegendInput;
use App\CoreFacturalo\Inputs\Common\PersonInput;
use App\CoreFacturalo\Inputs\Documents\Partials\ChargeInput;
use App\CoreFacturalo\Inputs\Documents\Partials\DetractionInput;
use App\CoreFacturalo\Inputs\Documents\Partials\DiscountInput;
use App\CoreFacturalo\Inputs\Documents\Partials\GuideInput;
use App\CoreFacturalo\Inputs\Documents\Partials\InvoiceInput;
use App\CoreFacturalo\Inputs\Documents\Partials\ItemInput;
use App\CoreFacturalo\Inputs\Documents\Partials\NoteInput;
use App\CoreFacturalo\Inputs\Documents\Partials\PerceptionInput;
use App\CoreFacturalo\Inputs\Documents\Partials\PrepaymentInput;
use App\CoreFacturalo\Inputs\Documents\Partials\RelatedInput;
use App\CoreFacturalo\Inputs\InputFunctions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Support\Str;

class DocumentInput
{
    public static function set($inputs, $service)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = InputFunctions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);
        $filename = InputFunctions::filename($company, $document_type_id, $series, $number);

        InputFunctions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        if(in_array($document_type_id, ['01', '03'])) {
            $array_partial = InvoiceInput::set($inputs);
            $invoice = $array_partial['invoice'];
            $note = null;
        } else {
            $array_partial = NoteInput::set($inputs);
            $note = $array_partial['note'];
            $invoice = null;
        }

        $inputs['type'] = $array_partial['type'];
        $inputs['group_id'] = $array_partial['group_id'];

        $establishment = EstablishmentInput::set($inputs['establishment_id']);
        $customer= PersonInput::set($inputs['customer_id']);

        return [
            'type' => $inputs['type'],
            'group_id' => $inputs['group_id'],
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => $establishment,
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'customer_id' => $inputs['customer_id'],
            'customer' => $customer,
            'currency_type_id' => $inputs['currency_type_id'],
            'purchase_order' => $inputs['purchase_order'],
            'exchange_rate_sale' => $inputs['exchange_rate_sale'],
            'total_prepayment' => InputFunctions::valueKeyInArray($inputs, 'total_prepayment', 0),
            'total_discount' => InputFunctions::valueKeyInArray($inputs, 'total_discount', 0),
            'total_charge' => InputFunctions::valueKeyInArray($inputs, 'total_charge', 0),
            'total_exportation' => InputFunctions::valueKeyInArray($inputs, 'total_exportation', 0),
            'total_free' => InputFunctions::valueKeyInArray($inputs, 'total_free', 0),
            'total_taxed' => $inputs['total_taxed'],
            'total_unaffected' => $inputs['total_unaffected'],
            'total_exonerated' => $inputs['total_exonerated'],
            'total_igv' => $inputs['total_igv'],
            'total_base_isc' => InputFunctions::valueKeyInArray($inputs, 'total_base_isc', 0),
            'total_isc' => InputFunctions::valueKeyInArray($inputs, 'total_isc', 0),
            'total_base_other_taxes' => InputFunctions::valueKeyInArray($inputs, 'total_base_other_taxes', 0),
            'total_other_taxes' => InputFunctions::valueKeyInArray($inputs, 'total_other_taxes', 0),
            'total_taxes' => $inputs['total_taxes'],
            'total_value' => $inputs['total_value'],
            'total' => $inputs['total'],
            'items' => ItemInput::set($inputs, $service),
            'charges' => ChargeInput::set($inputs),
            'discounts' => DiscountInput::set($inputs),
            'prepayments' => PrepaymentInput::set($inputs),
            'guides' => GuideInput::set($inputs),
            'related' => RelatedInput::set($inputs),
            'perception' => PerceptionInput::set($inputs),
            'detraction' => DetractionInput::set($inputs),
            'legends' => LegendInput::set($inputs),
            'invoice' => $invoice,
            'note' => $note,
            'actions' => ActionInput::set($inputs),
        ];
    }
}