<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Retentions;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransformApi\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransformApi\Retentions\Partials\DocumentInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Retention;
use Illuminate\Support\Str;

class RetentionInput
{
    public static function transform($inputs)
    {
        $document_type_id = $inputs['codigo_tipo_documento'];
        $series = $inputs['serie_documento'];
        $number = $inputs['numero_documento'];
        $date_of_issue = $inputs['fecha_de_emision'];
        $time_of_issue = $inputs['hora_de_emision'];
        $retention_type_id = $inputs['codigo_tipo_retencion'];
        $observations = $inputs['observaciones'];
        $totals = $inputs['totales'];
        $total_retention = array_key_exists('total_retenido', $totals)?$totals['total_retenido']:0;
        $total = array_key_exists('total_pagado', $totals)?$totals['total_pagado']:0;

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Retention::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);
        $currency_type_id = 'PEN';

        Functions::validateDocumentTypeId($document_type_id, ['20']);
        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Retention::class);

        $legends = [
            [
                'code' => 1000,
                'value' => 'SON:'.NumberLetter::convertToLetter($total)
            ]
        ];

        return [
            'type' => 'retention',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment' => EstablishmentInput::transform($inputs),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'supplier' => PersonInput::transform($inputs['datos_del_proveedor']),
            'retention_type_id' => $retention_type_id,
            'observations' => $observations,
            'currency_type_id' => $currency_type_id,
            'total_retention' => $total_retention,
            'total' => $total,
            'details' => DocumentInput::transform($inputs),
            'legends' => $legends,
            'optional' => null,
            'actions' => ActionInput::transform($inputs),
        ];
    }
}