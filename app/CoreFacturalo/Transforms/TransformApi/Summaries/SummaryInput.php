<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Summaries;

use App\CoreFacturalo\Transforms\TransformApi\Common\ActionInput;
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
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $date_of_reference = $inputs['fecha_de_emision_de_documentos'];
        $date_of_issue = date('Y-m-d');
        $summary_status_type_id = $inputs['codigo_tipo_proceso'];


        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = $company->number.'-'.$identifier;

        if ($summary_status_type_id === '1') {
            $documents = self::findDocuments($soap_type_id, $date_of_reference);
        } elseif ($summary_status_type_id === '3') {
            $documents = array_key_exists('documentos', $inputs)?$inputs['documentos']:[];
            $documents = self::verifyDocuments($soap_type_id, $date_of_reference, $documents);
        } else {
        throw new Exception("El código de tipo de proceso {$summary_status_type_id} es inválido");
    }

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
            'actions' => ActionInput::transform($inputs),
        ];
    }



    private static function findDocuments($soap_type_id, $date_of_reference)
    {
        $documents = Document::where('soap_type_id', $soap_type_id)
                            ->where('date_of_issue', $date_of_reference)
                            ->where('group_id', '02')
                            ->whereUser()
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
                                ->whereUser()
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