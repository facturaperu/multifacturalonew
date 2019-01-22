<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\TransformFunctions;

class NoteTransform
{
    public static function transform($inputs)
    {
        if(in_array($inputs['codigo_tipo_documento'], ['07', '08'])) {
            $affected_document = TransformFunctions::valueKeyInArray($inputs, 'documento_afectado', []);

            return [
                'note_credit_or_debit_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_nota'),
                'note_description' => TransformFunctions::valueKeyInArray($inputs, 'motivo_o_sustento_de_nota'),
                'document_type_id' => TransformFunctions::valueKeyInArray($affected_document, 'codigo_tipo_documento'),
                'series' => TransformFunctions::valueKeyInArray($affected_document, 'serie_documento'),
                'number' => TransformFunctions::valueKeyInArray($affected_document, 'numero_documento'),
            ];
        }
        return null;
    }
}