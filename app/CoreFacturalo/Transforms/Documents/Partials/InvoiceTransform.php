<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\Functions;

class InvoiceTransform
{
    public static function transform($inputs)
    {
        return [
            'operation_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_operacion'),
            'date_of_due' => Functions::valueKeyInArray($inputs, 'fecha_de_vencimiento'),
        ];
    }
}