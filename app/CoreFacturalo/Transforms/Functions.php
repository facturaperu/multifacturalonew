<?php

namespace App\CoreFacturalo\Transforms;

use App\Models\Tenant\Series;
use Exception;

class Functions
{
    public static function newNumber($soap_type_id, $document_type_id, $series, $number, $model)
    {
        if ($number === '#') {
            $document = $model::select('number')
                ->where('soap_type_id', $soap_type_id)
                ->where('document_type_id', $document_type_id)
                ->where('series', $series)
                ->orderBy('number', 'desc')
                ->first();
            return ($document)?(int)$document->number+1:1;
        }
        return $number;
    }

    public static function filename($company, $document_type_id, $series, $number)
    {
        return join('-', [$company->number, $document_type_id, $series, $number]);
    }

    public static function validateDocumentTypeId($document_type_id, $document_types)
    {
        if(!in_array($document_type_id, $document_types)) {
            throw new Exception("El código tipo de documento {$document_type_id} es incorrecto.");
        }
    }

    public static function validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, $model)
    {
        $document = $model::where('soap_type_id', $soap_type_id)
            ->where('document_type_id', $document_type_id)
            ->where('series', $series)
            ->where('number', $number)
            ->first();
        if($document) {
            throw new Exception("El documento: {$document_type_id} {$series}-{$number} ya se encuentra registrado.");
        }
    }

    public static function validateSeries($series_number, $document_type_id, $establishment_id)
    {
        $series = Series::where('establishment_id', $establishment_id)
            ->where('document_type_id', $document_type_id)
            ->where('number', $series_number)
            ->first();
        if(!$series) {
            throw new Exception("La serie ingresa no corresponde al establecimiento o al tipo de  documento");
        }
    }

    public static function findSeries($series_id)
    {
        if(!$series_id) {
            throw new Exception("La serie es requerida");
        }

        return Series::find($series_id)->number;
    }

    public static function validateSummaryStatusTypeId($summary_status_type_id)
    {
        if(!in_array($summary_status_type_id, ['1', '2', '3'], true)) {
            throw new Exception("El código de tipo de proceso {$summary_status_type_id} es inválido");
        }
    }
}