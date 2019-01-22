<?php

namespace App\CoreFacturalo\Transforms\Dispatches;

use App\CoreFacturalo\Transforms\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Dispatches\Partials\DeliveryTransform;
use App\CoreFacturalo\Transforms\Dispatches\Partials\DispatcherTransform;
use App\CoreFacturalo\Transforms\Dispatches\Partials\DriverTransform;
use App\CoreFacturalo\Transforms\Dispatches\Partials\ItemTransform;
use App\CoreFacturalo\Transforms\Dispatches\Partials\OriginTransform;
use App\CoreFacturalo\Transforms\TransformFunctions;

class DispatchTransform
{
    public static function transform($inputs)
    {
        return [
            'series' => TransformFunctions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => TransformFunctions::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => TransformFunctions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => TransformFunctions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor']),
            'observations' => TransformFunctions::valueKeyInArray($inputs, 'observaciones'),
            'transport_mode_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_modo_transporte'),
            'transfer_reason_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_motivo_traslado'),
            'transfer_reason_description' => TransformFunctions::valueKeyInArray($inputs, 'descripcion_motivo_traslado'),
            'date_of_shipping' => TransformFunctions::valueKeyInArray($inputs, 'fecha_de_traslado'),
            'transshipment_indicator' => TransformFunctions::valueKeyInArray($inputs, 'indicador_de_transbordo'),
            'port_code' => TransformFunctions::valueKeyInArray($inputs, 'codigo_de_puerto'),
            'unit_type_id' => TransformFunctions::valueKeyInArray($inputs, 'unidad_peso_total'),
            'total_weight' => TransformFunctions::valueKeyInArray($inputs, 'peso_total'),
            'packages_number' => TransformFunctions::valueKeyInArray($inputs, 'numero_de_bultos'),
            'container_number' => TransformFunctions::valueKeyInArray($inputs, 'numero_de_contenedor'),
            'license_plate' => TransformFunctions::valueKeyInArray($inputs, 'numero_de_placa'),
            'origin' => OriginTransform::transform($inputs),
            'delivery' => DeliveryTransform::transform($inputs),
            'dispatcher' => DispatcherTransform::transform($inputs),
            'driver' => DriverTransform::transform($inputs),
            'items' => ItemTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),
        ];
    }
}