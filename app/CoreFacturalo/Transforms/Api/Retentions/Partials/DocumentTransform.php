<?php

namespace App\CoreFacturalo\Transforms\Api\Retentions\Partials;

use App\CoreFacturalo\Transforms\Api\Common\ExchangeTransform;
use App\CoreFacturalo\Transforms\Api\Common\PaymentTransform;
use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'document_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_documento'),
                    'series' => FunctionsTransform::valueKeyInArray($row, 'serie_documento'),
                    'number' => FunctionsTransform::valueKeyInArray($row, 'numero_documento'),
                    'date_of_issue' => FunctionsTransform::valueKeyInArray($row, 'fecha_de_emision'),
                    'currency_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_moneda'),
                    'total_document' => FunctionsTransform::valueKeyInArray($row, 'total_documento'),
                    'payments' => PaymentTransform::transform($row),
                    'exchange_rate' => ExchangeTransform::transform($row),
                    'date_of_retention' => FunctionsTransform::valueKeyInArray($row, 'fecha_de_retencion'),
                    'total_retention' => FunctionsTransform::valueKeyInArray($row, 'total_retenido'),
                    'total_to_pay' => FunctionsTransform::valueKeyInArray($row, 'total_a_pagar'),
                    'total_payment' => FunctionsTransform::valueKeyInArray($row, 'total_pagado'),
                ];
            }

            return $documents;
        }
        return null;
    }
}