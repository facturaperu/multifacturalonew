<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\TransformFunctions;

class InvoiceTransform
{
    public static function transform($inputs)
    {
        if(in_array($inputs['codigo_tipo_documento'], ['01', '03'])) {
            return [
                'operation_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_operacion'),
                'date_of_due' => TransformFunctions::valueKeyInArray($inputs, 'fecha_de_vencimiento'),
            ];
        }
        return null;
    }
}