<?php

namespace App\CoreFacturalo\Inputs\Dispatches;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Inputs\Common\LegendInput;
use App\CoreFacturalo\Inputs\Common\PersonInput;
use App\CoreFacturalo\Inputs\Dispatches\Partials\DeliveryInput;
use App\CoreFacturalo\Inputs\Dispatches\Partials\DispatcherInput;
use App\CoreFacturalo\Inputs\Dispatches\Partials\DriverInput;
use App\CoreFacturalo\Inputs\Dispatches\Partials\ItemInput;
use App\CoreFacturalo\Inputs\Dispatches\Partials\OriginInput;
use App\CoreFacturalo\Inputs\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use Illuminate\Support\Str;

class DispatchInput
{
    public static function set($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

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
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'customer_id' => $inputs['customer_id'],
            'customer' => PersonInput::set($inputs['customer_id']),
            'observations' => $inputs['observations'],
            'transport_mode_type_id' => $inputs['transport_mode_type_id'],
            'transfer_reason_type_id' => $inputs['transfer_reason_type_id'],
            'transfer_reason_description' => $inputs['transfer_reason_description'],
            'date_of_shipping' => $inputs['date_of_shipping'],
            'transshipment_indicator' => $inputs['transshipment_indicator'],
            'port_code' => $inputs['port_code'],
            'unit_type_id' => $inputs['unit_type_id'],
            'total_weight' => $inputs['total_weight'],
            'packages_number' => $inputs['packages_number'],
            'container_number' => $inputs['container_number'],
            'license_plate' => $inputs['license_plate'],
            'origin' => OriginInput::set($inputs),
            'delivery' => DeliveryInput::set($inputs),
            'dispatcher' => DispatcherInput::set($inputs),
            'driver' => DriverInput::set($inputs),
            'items' => ItemInput::set($inputs),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
        ];
    }
}