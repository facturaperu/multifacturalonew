<?php

namespace App\CoreFacturalo\Transforms\Api\Web\Documents;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Voided;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class VoidedInput
{
    public static function transform($inputs)
    {
        $date_of_reference = $inputs['date_of_reference'];
        $documents = $inputs['documents'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $date_of_issue = Carbon::now()->format('Y-m-d');
        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = self::filename($identifier);

        return [
            'type' => 'voided',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
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
        $voided = Voided::where('soap_type_id', $soap_type_id)
                            ->where('date_of_issue', $date_of_issue)
                            ->get();
        $numeration = count($voided) + 1;

        return join('-', ['RA', Carbon::parse($date_of_issue)->format('Ymd'), $numeration]);
    }

    private static function filename($identifier)
    {
        $company = Company::active();
        return $company->number.'-'.$identifier;
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
                                ->where('group_id', '01')
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