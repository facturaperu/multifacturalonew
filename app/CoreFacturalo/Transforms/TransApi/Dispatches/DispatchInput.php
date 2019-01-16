<?php

namespace App\CoreFacturalo\Transforms\TransApi\Dispatches;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransApi\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials\DeliveryInput;
use App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials\DispatcherInput;
use App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials\DriverInput;
use App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials\ItemInput;
use App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials\OriginInput;
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

        $origin = OriginInput::transform($inputs);
        $delivery = DeliveryInput::transform($inputs);
        $dispatcher = DispatcherInput::transform($inputs);
        $diver = DriverInput::transform($inputs);
        $license_plate = $inputs['numero_de_placa'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Dispatch::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateDocumentTypeId($document_type_id, ['09']);
        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Dispatch::class);

        $array_establishment = EstablishmentInput::transform($inputs);
        $array_supplier = PersonInput::transform($inputs['datos_del_cliente_o_receptor']);

        Functions::validateSeries($series, $document_type_id, $array_establishment['establishment_id']);

        $items = ItemInput::transform($inputs);
        $optional = null;
        $legends = null;

        return [
            'type' => 'dispatch',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment_id' => $array_establishment['establishment_id'],
            'establishment' => $array_establishment['establishment'],
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'supplier_id' => $array_supplier['person_id'],
            'supplier' => $array_supplier['person'],
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
            'origin' => $origin,
            'delivery' => $delivery,
            'dispatcher' => $dispatcher,
            'diver' => $diver,
            'license_plate' => $license_plate,
            'items' => $items,
            'legends' => $legends,
            'optional' => $optional,
            'actions' => ActionInput::transform($inputs),
        ];
    }
}