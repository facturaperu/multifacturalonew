<?php

namespace App\CoreFacturalo\Formats;

use App\Models\Tenant\Document;
use App\Models\Tenant\Series;
use Carbon\Carbon;
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

//    public static function findSeries($series_id)
//    {
//        if(!$series_id) {
//            throw new Exception("La serie es requerida");
//        }
//
//        return Series::find($series_id)->number;
//    }

    public static function validateSummaryStatusTypeId($summary_status_type_id)
    {
        if(!in_array($summary_status_type_id, ['1', '2', '3'], true)) {
            throw new Exception("El código de tipo de proceso {$summary_status_type_id} es inválido");
        }
    }

    public static function identifier($soap_type_id, $date_of_issue, $model)
    {
        $documents = $model::where('soap_type_id', $soap_type_id)
            ->where('date_of_issue', $date_of_issue)
            ->whereUser()
            ->get();
        $numeration = count($documents) + 1;

        switch (get_class($model)) {
            case 'Voided':
                $prefix = 'RA';
                break;
            default:
                $prefix = 'RC';
                break;
        }

        return join('-', [$prefix, Carbon::parse($date_of_issue)->format('Ymd'), $numeration]);
    }

    public static function findDocumentsBySummary($soap_type_id, $date_of_reference)
    {
        $documents = Document::where('soap_type_id', $soap_type_id)
            ->where('date_of_issue', $date_of_reference)
            ->where('group_id', '02')
            ->get();

        if(count($documents) === 0) {
            throw new Exception("No se encontraron documentos con la fecha {$date_of_reference}");
        }
        $aux_documents = [];
        foreach ($documents as $doc)
        {
            $aux_documents[] = [
                'document_id' => $doc->id
            ];
        }

        return $aux_documents;
    }

    public static function verifyDocumentsByVoided($soap_type_id, $date_of_reference, $documents, $model)
    {
        $group_id = (get_class($model) === 'summary')?'02':'01';
        $aux_documents = [];
        foreach ($documents as $doc)
        {
            $external_id = $doc['external_id'];
            $description = $doc['motivo_anulacion'];

            $document = Document::where('soap_type_id', $soap_type_id)
                ->where('external_id', $external_id)
                ->where('group_id', $group_id)
                ->where('date_of_issue', $date_of_reference)
                ->first();
            if(!$document) {
                throw new Exception("El documento con codigo externo {$external_id} no es encontró");
            }
            $aux_documents[] = [
                'document_id' => $document->id,
                'description' => $description
            ];
        }
        if(count($aux_documents) === 0) {
            throw new Exception("No se enviaron documentos para la anulación");
        }

        return $aux_documents;
    }
}