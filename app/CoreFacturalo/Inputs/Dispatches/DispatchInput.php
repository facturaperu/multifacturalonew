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
use App\CoreFacturalo\Inputs\InputFunctions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use Illuminate\Support\Str;

class DispatchInput
{
    public static function set($inputs, $service)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = InputFunctions::newNumber($soap_type_id, $document_type_id, $series, $number, Dispatch::class);
        $filename = InputFunctions::filename($company, $document_type_id, $series, $number);

        InputFunctions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Dispatch::class);

        $establishment_array = EstablishmentInput::set($inputs, $service);
        $customer_array = PersonInput::set($inputs, 'customer', $service);

        return [
            'type' => 'dispatch',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $establishment_array['establishment_id'],
            'establishment' => $establishment_array['establishment'],
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'customer_id' => $customer_array['person_id'],
            'customer' => $customer_array['person'],
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
            'items' => ItemInput::set($inputs, $service),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
        ];
    }
}