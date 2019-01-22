<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class InvoiceInput
{
    public static function set($inputs)
    {
        if(array_key_exists('invoice', $inputs)) {
            $document_type_id = $inputs['document_type_id'];
            $invoice = $inputs['invoice'];
            $operation_type_id = $invoice['operation_type_id'];
            $date_of_due = $invoice['date_of_due'];

            return [
                'type' => 'invoice',
                'group_id' => ($document_type_id === '01')?'01':'02',
                'invoice' => [
                    'operation_type_id' => $operation_type_id,
                    'date_of_due' => $date_of_due,
                ]
            ];
        }
        return null;
    }
}