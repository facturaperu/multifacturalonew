<?php

namespace App\CoreFacturalo\Transforms\Api\Dispatches;

use App\CoreFacturalo\Transforms\Api\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Api\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Api\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Api\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Api\Dispatches\Partials\DeliveryTransform;
use App\CoreFacturalo\Transforms\Api\Dispatches\Partials\DispatcherTransform;
use App\CoreFacturalo\Transforms\Api\Dispatches\Partials\DriverTransform;
use App\CoreFacturalo\Transforms\Api\Dispatches\Partials\ItemTransform;
use App\CoreFacturalo\Transforms\Api\Dispatches\Partials\OriginTransform;
use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class DispatchTransform
{
    public static function transform($inputs)
    {
        $transform_inputs = [
            'series' => FunctionsTransform::valueKeyInArray($inputs, 'serie_documento'),
            'number' => FunctionsTransform::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'establishment_id' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer_id' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor'], 'customer'),
            'observations' => FunctionsTransform::valueKeyInArray($inputs, 'observaciones'),
            'transport_mode_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_modo_transporte'),
            'transfer_reason_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_motivo_traslado'),
            'transfer_reason_description' => FunctionsTransform::valueKeyInArray($inputs, 'descripcion_motivo_traslado'),
            'date_of_shipping' => FunctionsTransform::valueKeyInArray($inputs, 'fecha_de_traslado'),
            'transshipment_indicator' => FunctionsTransform::valueKeyInArray($inputs, 'indicador_de_transbordo'),
            'port_code' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_de_puerto'),
            'unit_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'unidad_peso_total'),
            'total_weight' => FunctionsTransform::valueKeyInArray($inputs, 'peso_total'),
            'packages_number' => FunctionsTransform::valueKeyInArray($inputs, 'numero_de_bultos'),
            'container_number' => FunctionsTransform::valueKeyInArray($inputs, 'numero_de_contenedor'),
            'license_plate' => FunctionsTransform::valueKeyInArray($inputs, 'numero_de_placa'),
            'origin' => OriginTransform::transform($inputs),
            'delivery' => DeliveryTransform::transform($inputs),
            'dispatcher' => DispatcherTransform::transform($inputs),
            'driver' => DriverTransform::transform($inputs),
            'items' => ItemTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs)
        ];

        FunctionsTransform::validateSeries($transform_inputs);

        return $transform_inputs;
    }
}