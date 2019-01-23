<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class InvoiceTransform
{
    public static function transform($inputs)
    {
        if(in_array($inputs['codigo_tipo_documento'], ['01', '03'])) {
            return [
                'operation_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_operacion'),
                'date_of_due' => FunctionsTransform::valueKeyInArray($inputs, 'fecha_de_vencimiento'),
            ];
        }
        return null;
    }
}