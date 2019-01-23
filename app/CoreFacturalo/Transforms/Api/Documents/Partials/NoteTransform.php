<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Exception;

class NoteTransform
{
    public static function transform($inputs)
    {
        if(in_array($inputs['codigo_tipo_documento'], ['07', '08'])) {
            $data = [
                'document_type_id' => FunctionsTransform::valueKeyInArray($inputs['documento_afectado'], 'codigo_tipo_documento'),
                'series' => FunctionsTransform::valueKeyInArray($inputs['documento_afectado'], 'serie_documento'),
                'number' => FunctionsTransform::valueKeyInArray($inputs['documento_afectado'], 'numero_documento')
            ];

            $affected_document = self::findAffectedDocument($data);

            return [
                'note_credit_or_debit_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_nota'),
                'note_description' => FunctionsTransform::valueKeyInArray($inputs, 'motivo_o_sustento_de_nota'),
                'affected_document_id' => $affected_document->id,
            ];
        }
        return null;
    }

    private static function findAffectedDocument($data)
    {
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $document =  Document::where('soap_type_id', $soap_type_id)
                        ->where('document_type_id', $data['document_type_id'])
                        ->where('series', $data['series'])
                        ->where('number', $data['number'])
                        //->where('state_type_id', '05')
                        ->first();

        if(!$document) {
            throw new Exception("El documento afectado no se encuentra registrado.");
        }
        return $document;
    }
}