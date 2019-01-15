<?php

namespace App\CoreFacturalo\Transforms;

use App\Models\Tenant\Document;
use App\Models\Tenant\Series;
use Exception;

class TransformFunctions
{
    public static function newNumber($soap_type_id, $document_type_id, $series, $number)
    {
        if ($number === '#') {
            $document = Document::select('number')
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

    public static function validateDocumentTypeId($document_type_id)
    {
        if(!in_array($document_type_id, ['01', '03', '07', '08'])) {
            throw new Exception("El código tipo de documento {$document_type_id} es incorrecto.");
        }
    }

    public static function validateUniqueDocument($soap_type_id, $document_type_id, $series, $number)
    {
        $document = Document::where('soap_type_id', $soap_type_id)
            ->where('document_type_id', $document_type_id)
            ->where('series', $series)
            ->where('number', $number)
            ->first();
        if($document) {
            throw new Exception("El documento: {$document_type_id} {$series}-{$number} ya se encuentra registrado.");
        }
    }

    public static function findSeries($series_id)
    {
        if(!$series_id) {
            throw new Exception("La serie es requerida");
        }

        return Series::find($series_id)->number;
    }
}