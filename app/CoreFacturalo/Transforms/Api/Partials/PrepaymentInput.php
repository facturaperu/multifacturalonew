<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class PrepaymentInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $prepayments = array_key_exists('prepayments', $inputs)?$inputs['prepayments']:null;
        } else {
            $prepayments = array_key_exists('anticipos', $inputs)?$inputs['anticipos']:null;
        }

        if(is_null($prepayments)) {
            return null;
        }

        $transform_prepayments = [];
        foreach ($prepayments as $row)
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

            $transform_prepayments[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
                'amount' => $amount
            ];
        }

        return $transform_prepayments;
    }
}