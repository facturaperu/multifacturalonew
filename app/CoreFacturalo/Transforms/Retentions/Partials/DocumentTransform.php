<?php

namespace App\CoreFacturalo\Transforms\Retentions\Partials;

use App\CoreFacturalo\Transforms\Common\ExchangeTransform;
use App\CoreFacturalo\Transforms\Common\PaymentTransform;
use App\CoreFacturalo\Transforms\Functions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'document_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_documento'),
                    'series' => Functions::valueKeyInArray($row, 'serie_documento'),
                    'number' => Functions::valueKeyInArray($row, 'numero_documento'),
                    'date_of_issue' => Functions::valueKeyInArray($row, 'fecha_de_emision'),
                    'currency_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_moneda'),
                    'total_document' => Functions::valueKeyInArray($row, 'total_documento'),
                    'payments' => PaymentTransform::transform($row),
                    'exchange_rate' => ExchangeTransform::transform($row),
                    'date_of_retention' => Functions::valueKeyInArray($row, 'fecha_de_retencion'),
                    'total_retention' => Functions::valueKeyInArray($row, 'total_retenido'),
                    'total_to_pay' => Functions::valueKeyInArray($row, 'total_a_pagar'),
                    'total_payment' => Functions::valueKeyInArray($row, 'total_pagado'),
                ];
            }

            return $documents;
        }
        return null;
    }
}