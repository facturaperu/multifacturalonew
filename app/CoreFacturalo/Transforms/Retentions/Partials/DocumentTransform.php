<?php

namespace App\CoreFacturalo\Transforms\Retentions\Partials;

use App\CoreFacturalo\Transforms\Common\ExchangeTransform;
use App\CoreFacturalo\Transforms\Common\PaymentTransform;
use App\CoreFacturalo\Transforms\TransformFunctions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'document_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_documento'),
                    'series' => TransformFunctions::valueKeyInArray($row, 'serie_documento'),
                    'number' => TransformFunctions::valueKeyInArray($row, 'numero_documento'),
                    'date_of_issue' => TransformFunctions::valueKeyInArray($row, 'fecha_de_emision'),
                    'currency_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_moneda'),
                    'total_document' => TransformFunctions::valueKeyInArray($row, 'total_documento'),
                    'payments' => PaymentTransform::transform($row),
                    'exchange_rate' => ExchangeTransform::transform($row),
                    'date_of_retention' => TransformFunctions::valueKeyInArray($row, 'fecha_de_retencion'),
                    'total_retention' => TransformFunctions::valueKeyInArray($row, 'total_retenido'),
                    'total_to_pay' => TransformFunctions::valueKeyInArray($row, 'total_a_pagar'),
                    'total_payment' => TransformFunctions::valueKeyInArray($row, 'total_pagado'),
                ];
            }

            return $documents;
        }
        return null;
    }
}