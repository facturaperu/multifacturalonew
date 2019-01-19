<?php

namespace App\CoreFacturalo\Formats\Documents;

use App\CoreFacturalo\Formats\Common\ActionFormat;
use App\CoreFacturalo\Formats\Common\EstablishmentFormat;
use App\CoreFacturalo\Formats\Common\LegendFormat;
use App\CoreFacturalo\Formats\Common\PersonFormat;
use App\CoreFacturalo\Formats\Documents\Partials\ItemFormat;
use App\CoreFacturalo\Formats\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Support\Str;

class DocumentFormat
{
    public static function format($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series_id'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        $establishment = EstablishmentFormat::format($inputs['establishment_id']);
        $person = PersonFormat::format($inputs['person_id']);
        $items = ItemFormat::format($inputs);
        $charges = array_key_exists('charges', $inputs)?$inputs['charges']:null;
        $discounts = array_key_exists('discounts', $inputs)?$inputs['discounts']:null;
        $prepayments = array_key_exists('prepayments', $inputs)?$inputs['prepayments']:null;
        $guides = array_key_exists('guides', $inputs)?$inputs['guides']:null;
        $related = array_key_exists('related', $inputs)?$inputs['related']:null;
        $perception = array_key_exists('perception', $inputs)?$inputs['perception']:null;
        $detraction = array_key_exists('detraction', $inputs)?$inputs['detraction']:null;
        $legends = LegendFormat::format($inputs);
        $actions = ActionFormat::format($inputs);

        if(in_array($document_type_id, ['01', '03'])) {
            $type = 'invoice';
            $note = null;
            $group_id = ($document_type_id === '01')?'01':'02';
            $invoice = [
                'date_of_due' => $inputs['date_of_due'],
                'operation_type_id' => $inputs['operation_type_id']
            ];
        } else {
            $invoice = null;
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
            'customer_id' => $inputs['person_id'],
            'customer' => $person,
            'currency_type_id' => $inputs['currency_type_id'],
            'purchase_order' => $inputs['purchase_order'],
            'exchange_rate_sale' => $inputs['exchange_rate_sale'],
            'total_prepayment' => array_key_exists('total_prepayment', $inputs)?$inputs['total_prepayment']:0,
            'total_discount' => array_key_exists('total_discount', $inputs)?$inputs['total_discount']:0,
            'total_charge' => array_key_exists('total_charge', $inputs)?$inputs['total_charge']:0,
            'total_exportation' => array_key_exists('total_exportation', $inputs)?$inputs['total_exportation']:0,
            'total_free' => array_key_exists('total_free', $inputs)?$inputs['total_free']:0,
            'total_taxed' => $inputs['total_taxed'],
            'total_unaffected' => $inputs['total_unaffected'],
            'total_exonerated' => $inputs['total_exonerated'],
            'total_igv' => $inputs['total_igv'],
            'total_base_isc' => array_key_exists('total_base_isc', $inputs)?$inputs['total_base_isc']:0,
            'total_isc' => array_key_exists('total_isc', $inputs)?$inputs['total_isc']:0,
            'total_base_other_taxes' => array_key_exists('total_base_other_taxes', $inputs)?$inputs['total_base_other_taxes']:0,
            'total_other_taxes' => array_key_exists('total_other_taxes', $inputs)?$inputs['total_other_taxes']:0,
            'total_taxes' => $inputs['total_taxes'],
            'total_value' => $inputs['total_value'],
            'total' => $inputs['total'],
            'items' => $items,
            'charges' => $charges,
            'discounts' => $discounts,
            'prepayments' => $prepayments,
            'guides' => $guides,
            'related' => $related,
            'perception' => $perception,
            'detraction' => $detraction,
            'legends' => $legends,
            'invoice' => $invoice,
            'note' => $note,
            'actions' => $actions,
        ];
    }
}