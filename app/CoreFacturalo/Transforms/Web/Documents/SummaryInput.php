<?php

namespace App\CoreFacturalo\Transforms\Api\Web\Documents;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class SummaryInput
{
    public static function transform($inputs)
    {
        $date_of_reference = $inputs['date_of_reference'];
        $process_type_id = $inputs['process_type_id'];
        $documents = array_key_exists('documents', $inputs)?$inputs['documents']:[];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $date_of_issue = Carbon::now()->format('Y-m-d');
        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = self::filename($identifier);

        if (in_array($process_type_id, ['1', '2'])) {
            $documents = self::findDocuments($soap_type_id, $date_of_reference);
        } else {
            $documents = self::verifyDocuments($soap_type_id, $date_of_reference, $documents);
        }

        return [
            'type' => 'summary',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'process_type_id' => $process_type_id,
            'ubl_version' => '2.0',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $date_of_reference,
            'identifier' => $identifier,
            'filename' => $filename,
            'documents' => $documents,
            'success' => true
        ];
    }

    private static function identifier($soap_type_id, $date_of_issue)
    {
        $summaries = Summary::where('soap_type_id', $soap_type_id)
                            ->where('date_of_issue', $date_of_issue)
                            ->get();
        $numeration = count($summaries) + 1;

        return join('-', ['RC', Carbon::parse($date_of_issue)->format('Ymd'), $numeration]);
    }

    private static function filename($identifier)
    {
        $company = Company::active();
        return $company->number.'-'.$identifier;
    }

    private static function validateProcessTypeId($process_type_id)
    {
        if(!in_array($process_type_id, ['1', '2', '3'], true)) {
            throw new Exception("El código de tipo de proceso {$process_type_id} es inválido");
        }
    }

    private static function findDocuments($soap_type_id, $date_of_reference)
    {
        $documents = Document::where('soap_type_id', $soap_type_id)
                            ->where('date_of_issue', $date_of_reference)
                            ->where('group_id', '02')
                            ->get();

        if(count($documents) === 0) {
            throw new Exception("No se encontraron documentos con la fecha {$date_of_reference}");
        }

        return $documents;
    }

    private static function idDocuments($documents)
    {
        $ids = [];
        foreach ($documents as $doc)
        {
            $ids[] = [
                'document_id' => is_array($doc)?$doc['id']:$doc->id
            ];
        }

        return $ids;
    }

    private static function verifyDocuments($soap_type_id, $date_of_reference, $documents)
    {
        $aux_documents = [];
        foreach ($documents as $doc)
        {
            $external_id = $doc['external_id'];
            $description = $doc['motivo_anulacion'];

            $document = Document::where('soap_type_id', $soap_type_id)
                                ->where('external_id', $external_id)
                                ->where('group_id', '02')
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