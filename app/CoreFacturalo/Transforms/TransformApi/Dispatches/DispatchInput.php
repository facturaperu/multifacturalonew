<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Dispatches;

use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials\DeliveryInput;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials\DispatcherInput;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials\DriverInput;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials\ItemInput;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials\OriginInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\ActionInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use Illuminate\Support\Str;

class DispatchInput
{
    public static function transform($inputs)
    {
        $document_type_id = $inputs['codigo_tipo_documento'];
        $series = $inputs['serie_documento'];
        $number = $inputs['numero_documento'];
        $date_of_issue = $inputs['fecha_de_emision'];
        $time_of_issue = $inputs['hora_de_emision'];
        $observations = $inputs['observaciones'];
        $transport_mode_type_id = $inputs['codigo_modo_transporte'];
        $transfer_reason_type_id = $inputs['codigo_motivo_traslado'];
        $transfer_reason_description = $inputs['descripcion_motivo_traslado'];
        $date_of_shipping = $inputs['fecha_de_traslado'];
        $port_code = $inputs['codigo_de_puerto'];
        $transshipment_indicator = $inputs['indicador_de_transbordo'];
        $unit_type_id = $inputs['unidad_peso_total'];
        $total_weight = $inputs['peso_total'];
        $packages_number = $inputs['numero_de_bultos'];
        $container_number = $inputs['numero_de_contenedor'];

        $license_plate = $inputs['numero_de_placa'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Dispatch::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateDocumentTypeId($document_type_id, ['09']);
        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Dispatch::class);

        return [
            'type' => 'dispatch',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment' => EstablishmentInput::transform($inputs),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'customer' => PersonInput::transform($inputs['datos_del_cliente_o_receptor']),
            'observations' => $observations,
            'transport_mode_type_id' => $transport_mode_type_id,
            'transfer_reason_type_id' => $transfer_reason_type_id,
            'transfer_reason_description' => $transfer_reason_description,
            'date_of_shipping' => $date_of_shipping,
            'transshipment_indicator' => $transshipment_indicator,
            'port_code' => $port_code,
            'unit_type_id' => $unit_type_id,
            'total_weight' => $total_weight,
            'packages_number' => $packages_number,
            'container_number' => $container_number,
            'license_plate' => $license_plate,
            'origin' => OriginInput::transform($inputs),
            'delivery' => DeliveryInput::transform($inputs),
            'dispatcher' => DispatcherInput::transform($inputs),
            'driver' => DriverInput::transform($inputs),
            'details' => ItemInput::transform($inputs),
            'legends' => null,
            'optional' => null,
            'actions' => ActionInput::transform($inputs),
        ];
    }
}