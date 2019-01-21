<?php

namespace App\CoreFacturalo\Inputs\Retentions\Partials;

use App\CoreFacturalo\Inputs\Common\ExchangeInput;
use App\CoreFacturalo\Inputs\Common\PaymentInput;

class DocumentInput
{
    public static function set($inputs)
    {
        if(key_exists('documents', $inputs)) {
            $documents = [];
            foreach ($inputs['documents'] as $row)
            {
                $document_type_id = $row['document_type_id'];
                $series = $row['series'];
                $number = $row['number'];
                $date_of_issue = $row['date_of_issue'];
                $currency_type_id = $row['currency_type_id'];
                $total_document = $row['total_document'];
                $date_of_retention = $row['date_of_retention'];
                $total_retention = $row['total_retention'];
                $total_to_pay = $row['total_to_pay'];
                $total_payment = $row['total_payment'];

                $documents[] = [
                    'document_type_id' => $document_type_id,
                    'series' => $series,
                    'number' => $number,
                    'date_of_issue' => $date_of_issue,
                    'currency_type_id' => $currency_type_id,
                    'total_document' => $total_document,
                    'payments' => PaymentInput::set($row),
                    'exchange_rate' => ExchangeInput::set($row),
                    'date_of_retention' => $date_of_retention,
                    'total_retention' => $total_retention,
                    'total_to_pay' => $total_to_pay,
                    'total_payment' => $total_payment,
                ];
            }

            return $documents;
        }
        return null;
    }
}