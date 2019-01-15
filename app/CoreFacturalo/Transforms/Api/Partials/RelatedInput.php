<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class RelatedInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $related = array_key_exists('related', $inputs)?$inputs['related']:null;
        } else {
            $related = array_key_exists('documentos_relacionados', $inputs)?$inputs['documentos_relacionados']:null;
        }

        if(is_null($related)) {
            return null;
        }

        $transform_related = [];
        foreach ($related as $row)
        {
            if($isWeb) {
                $document_type_id = $row['document_type_id'];
                $number = $row['number'];
                $amount = $row['amount'];
            } else {
                $number = $row['numero'];
                $document_type_id = $row['tipo_de_documento'];
                $amount = $row['monto'];
            }

            $transform_related[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
                'amount' => $amount
            ];
        }

        return $transform_related;
    }
}