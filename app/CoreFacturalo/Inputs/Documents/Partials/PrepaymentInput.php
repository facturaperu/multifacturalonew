<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class PrepaymentInput
{
    public static function set($inputs)
    {
        if(array_key_exists('prepayments', $inputs)) {
            if($inputs['prepayments']) {
                $prepayments = [];
                foreach ($inputs['prepayments'] as $row)
                {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];
                    $amount = $row['amount'];

                    $prepayments[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                        'amount' => $amount
                    ];
                }
                return $prepayments;
            }
        }
        return null;
    }
}