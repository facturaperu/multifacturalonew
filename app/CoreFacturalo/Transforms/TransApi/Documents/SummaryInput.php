<?php

namespace App\CoreFacturalo\Transforms\TransApi\Documents;

use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransApi\Common\ActionInput;
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
        $date_of_reference = $inputs['fecha_de_emision_de_documentos'];
        $summary_status_type_id = $inputs['codigo_tipo_proceso'];
        $documents = array_key_exists('documentos', $inputs)?$inputs['documentos']:[];

        Functions::validateSummaryStatusTypeId($summary_status_type_id);

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $date_of_issue = Carbon::now()->format('Y-m-d');
        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = self::filename($identifier);

        if (in_array($summary_status_type_id, ['1', '2'])) {
            $documents = self::findDocuments($soap_type_id, $date_of_reference);
        } else {
            $documents = self::verifyDocuments($soap_type_id, $date_of_reference, $documents);
        }

        $documents = self::idDocuments($documents);

        return [
            'type' => 'summary',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'summary_status_type_id' => $summary_status_type_id,
            'ubl_version' => '2.0',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $date_of_reference,
            'identifier' => $identifier,
            'filename' => $filename,
            'documents' => $documents,
            'success' => true,
            'actions' => ActionInput::transform($inputs),
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