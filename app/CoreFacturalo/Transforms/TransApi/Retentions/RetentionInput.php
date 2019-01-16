<?php

namespace App\CoreFacturalo\Transforms\TransApi\Retentions;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransApi\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransApi\Retentions\Partials\DocumentInput;
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

        $array_establishment = EstablishmentInput::transform($inputs);
        $array_supplier = PersonInput::transform($inputs['datos_del_proveedor']);

        Functions::validateSeries($series, $document_type_id, $array_establishment['establishment_id']);

        $documents = DocumentInput::transform($inputs);
        $optional = null;// OptionalInput::transform($inputs);

        $legends = [
            [
                'code' => 1000,
                'value' => NumberLetter::convertToLetter($total)
            ]
        ];

        return [
            'type' => 'retention',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment_id' => $array_establishment['establishment_id'],
            'establishment' => $array_establishment['establishment'],
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'supplier_id' => $array_supplier['person_id'],
            'supplier' => $array_supplier['person'],
            'retention_type_id' => $retention_type_id,
            'observations' => $observations,
            'currency_type_id' => $currency_type_id,
            'total_retention' => $total_retention,
            'total' => $total,
            'documents' => $documents,
            'legends' => $legends,
            'optional' => $optional,
            'actions' => ActionInput::transform($inputs),
        ];
    }
}