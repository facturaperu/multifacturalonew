<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\TransWeb\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransWeb\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransWeb\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\ChargeInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\DetractionInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\DiscountInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\GuideInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\ItemInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\LegendInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\OptionalInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\PerceptionInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\PrepaymentInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\Partials\RelatedInput;
use App\CoreFacturalo\Transforms\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Exception;
use Illuminate\Support\Str;

class DocumentInput
{
    public static function transform($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = Functions::findSeries($inputs['series_id']);
        $number = $inputs['number'];
        $date_of_issue = $inputs['date_of_issue'];
        $time_of_issue = $inputs['time_of_issue'];
        $currency_type_id = $inputs['currency_type_id'];
        $purchase_order = array_key_exists('purchase_order', $inputs)?$inputs['purchase_order']:null;
        $exchange_rate_sale = $inputs['exchange_rate_sale'];

        $total_prepayment = $inputs['total_prepayment'];
        $total_discount = $inputs['total_discount'];
        $total_charge = $inputs['total_charge'];
        $total_exportation = $inputs['total_exportation'];
        $total_free = $inputs['total_free'];
        $total_taxed = $inputs['total_taxed'];
        $total_unaffected = $inputs['total_unaffected'];
        $total_exonerated = $inputs['total_exonerated'];
        $total_igv = $inputs['total_igv'];
        $total_base_isc = $inputs['total_base_isc'];
        $total_isc = $inputs['total_isc'];
        $total_base_other_taxes = $inputs['total_base_other_taxes'];
        $total_other_taxes = $inputs['total_other_taxes'];
        $total_taxes = $inputs['total_taxes'];
        $total_value = $inputs['total_value'];
        $total = $inputs['total'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        $array_establishment = EstablishmentInput::transform($inputs);

        if(!$inputs['customer_id']) {
            throw new Exception("El cliente es requerido");
        }
        $array_customer = PersonInput::transform($inputs['customer_id']);

        $items = ItemInput::transform($inputs);
        $charges = ChargeInput::transform($inputs);
        $discounts = DiscountInput::transform($inputs);
        $prepayments = PrepaymentInput::transform($inputs);
        $guides = GuideInput::transform($inputs);
        $related = RelatedInput::transform($inputs);
        $perception = PerceptionInput::transform($inputs);
        $detraction = DetractionInput::transform($inputs);
        $optional = OptionalInput::transform($inputs);
        $legends = LegendInput::transform($inputs);

        $legends[] = [
            'code' => 1000,
            'value' => NumberLetter::convertToLetter($total)
        ];

        $invoice = null;
        $note = null;
        $type = 'invoice';
        $group_id = null;

        if(in_array($document_type_id, ['01', '03'])) {
            $group_id = ($document_type_id === '01')?'01':'02';
            $invoice = [
                'date_of_due' => $inputs['date_of_due'],
                'operation_type_id' => $inputs['operation_type_id']
            ];
        } else {
            $aff_document_id = $inputs['affected_document_id'];
            $note_credit_or_debit_type_id = $inputs['note_credit_or_debit_type_id'];
            $note_description = $inputs['note_description'];
            $aff_document = Document::find($aff_document_id);
            $group_id = $aff_document->group_id;
            if ($document_type_id === '07') {
                $note_type = 'credit';
                $note_credit_type_id = $note_credit_or_debit_type_id;
                $note_debit_type_id = null;
                $type = 'credit';
            } else {
                $note_type = 'debit';
                $note_credit_type_id = null;
                $note_debit_type_id = $note_credit_or_debit_type_id;
                $type = 'debit';
            }

            $note = [
                'note_type' => $note_type,
                'note_credit_type_id' => $note_credit_type_id,
                'note_debit_type_id' => $note_debit_type_id,
                'note_description' => $note_description,
                'affected_document_id' => $aff_document->id,
            ];
        }

        return [
            'type' => $type,
            'group_id' => $group_id,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment_id' => $array_establishment['establishment_id'],
            'establishment' => $array_establishment['establishment'],
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'customer_id' => $array_customer['person_id'],
            'customer' => $array_customer['person'],
            'currency_type_id' => $currency_type_id,
            'purchase_order' => $purchase_order,
            'exchange_rate_sale' => $exchange_rate_sale,
            'total_prepayment' => $total_prepayment,
            'total_discount' => $total_discount,
            'total_charge' => $total_charge,
            'total_exportation' => $total_exportation,
            'total_free' => $total_free,
            'total_taxed' => $total_taxed,
            'total_unaffected' => $total_unaffected,
            'total_exonerated' => $total_exonerated,
            'total_igv' => $total_igv,
            'total_base_isc' => $total_base_isc,
            'total_isc' => $total_isc,
            'total_base_other_taxes' => $total_base_other_taxes,
            'total_other_taxes' => $total_other_taxes,
            'total_taxes' => $total_taxes,
            'total_value' => $total_value,
            'total' => $total,
            'items' => $items,
            'charges' => $charges,
            'discounts' => $discounts,
            'prepayments' => $prepayments,
            'guides' => $guides,
            'related' => $related,
            'perception' => $perception,
            'detraction' => $detraction,
            'legends' => $legends,
            'optional' => $optional,
            'invoice' => $invoice,
            'note' => $note,
            'actions' => ActionInput::transform($inputs),
        ];
    }
}