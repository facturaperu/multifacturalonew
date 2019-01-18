<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Retentions\Partials;

use App\CoreFacturalo\Transforms\TransformApi\Common\PaymentInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\ExchangeInput;

class DocumentInput
{
    public static function transform($inputs)
    {
        $documents = array_key_exists('documentos', $inputs)?$inputs['documentos']:[];

        if(count($documents) === 0) {
            return null;
        }

        $transform_documents = [];
        foreach ($documents as $row)
        {
            $document_type_id = $row['codigo_tipo_documento'];
            $series = $row['serie_documento'];
            $number = $row['numero_documento'];
            $date_of_issue = $row['fecha_de_emision'];
            $currency_type_id = $row['codigo_tipo_moneda'];
            $total_document = $row['total_documento'];
            $date_of_retention = $row['fecha_de_retencion'];
            $total_retention = $row['total_retenido'];
            $total_to_pay = $row['total_a_pagar'];
            $total_payment = $row['total_pagado'];

            $transform_documents[] = [
                'document_type_id' => $document_type_id,
                'series' => $series,
                'number' => $number,
                'date_of_issue' => $date_of_issue,
                'currency_type_id' => $currency_type_id,
                'total_document' => $total_document,
                'payments' => PaymentInput::transform($row),
                'exchange_rate' => ExchangeInput::transform($row),
                'date_of_retention' => $date_of_retention,
                'total_retention' => $total_retention,
                'total_to_pay' => $total_to_pay,
                'total_payment' => $total_payment,
            ];
        }

        return $transform_documents;
    }
}