<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

class PrepaymentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('anticipos', $inputs)) {
            $prepayments = [];
            foreach ($inputs['anticipos'] as $row)
            {
                $prepayments[] = [
                    'number' => $row['numero'],
                    'document_type_id' => $row['codigo_tipo_documento'],
                    'amount' => $row['monto']
                ];
            }

            return $prepayments;
        }
        return null;
    }
}