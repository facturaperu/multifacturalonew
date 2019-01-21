<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\Functions;

class NoteTransform
{
    public static function transform($inputs)
    {
        $affected_document = Functions::valueKeyInArray($inputs, 'documento_afectado', []);

        return [
            'note_credit_or_debit_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_nota'),
            'note_description' => Functions::valueKeyInArray($inputs, 'motivo_o_sustento_de_nota'),
            'document_type_id' => Functions::valueKeyInArray($affected_document, 'codigo_tipo_documento'),
            'series' => Functions::valueKeyInArray($affected_document, 'serie_documento'),
            'number' => Functions::valueKeyInArray($affected_document, 'numero_documento'),
        ];
    }
}