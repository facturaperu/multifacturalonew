<?php

namespace App\CoreFacturalo\Transforms\Api;

class InvoiceInput
{
    public static function transform($inputs, $document, $isWeb)
    {
        if($isWeb) {
            $date_of_due = $inputs['date_of_due'];
            $operation_type_id = $inputs['operation_type_id'];
        } else {
            $date_of_due = $inputs['fecha_de_vencimiento'];
            $operation_type_id = $inputs['codigo_tipo_operacion'];
        }

        return [
            'type' => 'invoice',
            'group_id' => ($document['document_type_id'] === '01')?'01':'02',
            'document_base' => [
                'date_of_due' => $date_of_due,
                'operation_type_id' => $operation_type_id
            ]
        ];
    }
}