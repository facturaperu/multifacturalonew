<?php

namespace App\CoreFacturalo\Transforms\Api;

use App\CoreFacturalo\Transforms\Api\Partials\ActionInput;
use App\CoreFacturalo\Transforms\Api\Partials\EstablishmentInput;
use App\CoreFacturalo\Transforms\Api\Partials\ExchangeInput;
use App\CoreFacturalo\Transforms\Api\Partials\LegendInput;
use App\CoreFacturalo\Transforms\Api\Partials\OptionalInput;
use App\CoreFacturalo\Transforms\Api\Partials\PaymentInput;
use App\CoreFacturalo\Transforms\Api\Partials\SupplierInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Str;

class RetentionInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $document_type_id = $inputs['document_type_id'];
            $series = self::findSeries($inputs['series_id']);
            $number = $inputs['number'];
            $date_of_issue = $inputs['date_of_issue'];
            $time_of_issue = $inputs['time_of_issue'];
            $retention_type_id = $inputs['retention_type_id'];
            $observations = $inputs['observations'];
            $total_retention = $inputs['total_retention'];
            $total = $inputs['total'];
        } else {
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
        }

        self::validateDocumentTypeId($document_type_id);

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = self::newNumber($soap_type_id, $document_type_id, $series, $number);
        $filename = self::filename($company, $document_type_id, $series, $number);
        $currency_type_id = 'PEN';

        self::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number);

        $array_establishment = EstablishmentInput::transform($inputs, $isWeb);
        $array_supplier = SupplierInput::transform($inputs, $isWeb);
        $documents = self::documentsTransform($inputs, $isWeb);
        $legends = LegendInput::transform($inputs, $isWeb);
        $optional = OptionalInput::transform($inputs, $isWeb);

        self::validateSeries($series, $document_type_id, $array_establishment['establishment_id']);

        return [
            'type' => 'retention',
            'actions' => ActionInput::transform($inputs, $isWeb),
            'retention' => [
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
                'supplier_id' => $array_supplier['supplier_id'],
                'supplier' => $array_supplier['supplier'],
                'retention_type_id' => $retention_type_id,
                'observations' => $observations,
                'currency_type_id' => $currency_type_id,
                'total_retention' => $total_retention,
                'total' => $total,
                'documents' => $documents,
                'legends' => $legends,
                'optional' => $optional
            ]
        ];
    }

    private static function newNumber($soap_type_id, $document_type_id, $series, $number)
    {
        if ($number === '#') {
            $retention = Retention::select('number')
                ->where('soap_type_id', $soap_type_id)
                ->where('document_type_id', $document_type_id)
                ->where('series', $series)
                ->orderBy('number', 'desc')
                ->first();
            return ($retention)?(int)$retention->number+1:1;
        }
        return $number;
    }

    private static function filename($company, $document_type_id, $series, $number)
    {
        return join('-', [$company->number, $document_type_id, $series, $number]);
    }

    private static function validateDocumentTypeId($document_type_id)
    {
        if(!in_array($document_type_id, ['20'])) {
            throw new Exception("El código tipo de documento {$document_type_id} es incorrecto.");
        }
    }

    private static function validateUniqueDocument($soap_type_id, $document_type_id, $series, $number)
    {
        $retention = Retention::where('soap_type_id', $soap_type_id)
            ->where('document_type_id', $document_type_id)
            ->where('series', $series)
            ->where('number', $number)
            ->first();
        if($retention) {
            throw new Exception("El documento: {$document_type_id} {$series}-{$number} ya se encuentra registrado.");
        }
    }

    private static function validateSeries($series_number, $document_type_id, $establishment_id)
    {
        $series = Series::where('establishment_id', $establishment_id)
            ->where('document_type_id', $document_type_id)
            ->where('number', $series_number)
            ->first();
        if(!$series) {
            throw new Exception("La serie ingresa no corresponde al establecimiento o al tipo de  documento");
        }
    }

    private static function findSeries($series_id)
    {
        if(!$series_id) {
            throw new Exception("La serie es requerida");
        }

        return Series::find($series_id)->number;
    }

    private static function documentsTransform($inputs, $isWeb)
    {
        if($isWeb) {
            $documents = array_key_exists('documents', $inputs)?$inputs['documents']:[];
        } else {
            $documents = array_key_exists('documentos', $inputs)?$inputs['documentos']:[];
        }

        if(count($documents) === 0) {
            return null;
        }

        $transform_documents = [];
        foreach ($documents as $row)
        {
            if($isWeb) {
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
            } else {
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
            }

            $payments = PaymentInput::transform($row, $isWeb);
            $exchange_rate = ExchangeInput::transform($row, $isWeb);

            $transform_documents[] = [
                'document_type_id' => $document_type_id,
                'series' => $series,
                'number' => $number,
                'date_of_issue' => $date_of_issue,
                'currency_type_id' => $currency_type_id,
                'total_document' => $total_document,
                'payments' => $payments,
                'exchange_rate' => $exchange_rate,
                'date_of_retention' => $date_of_retention,
                'total_retention' => $total_retention,
                'total_to_pay' => $total_to_pay,
                'total_payment' => $total_payment,
            ];
        }

        return $transform_documents;
    }
}