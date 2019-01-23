<?php

namespace App\CoreFacturalo\Inputs\Retentions;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Inputs\Common\LegendInput;
use App\CoreFacturalo\Inputs\Common\PersonInput;
use App\CoreFacturalo\Inputs\InputFunctions;
use App\CoreFacturalo\Inputs\Retentions\Partials\DocumentInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Retention;
use Illuminate\Support\Str;

class RetentionInput
{
    public static function set($inputs, $service)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = InputFunctions::newNumber($soap_type_id, $document_type_id, $series, $number, Retention::class);
        $filename = InputFunctions::filename($company, $document_type_id, $series, $number);
        $currency_type_id = 'PEN';

        InputFunctions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Retention::class);

        $establishment = EstablishmentInput::set($inputs['establishment_id']);
        $supplier= PersonInput::set($inputs['supplier_id']);

        $inputs['type'] = 'retention';

        return [
            'type' => $inputs['type'],
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => $establishment,
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'supplier_id' => $inputs['supplier_id'],
            'supplier' => $supplier,
            'retention_type_id' => $inputs['retention_type_id'],
            'observations' => $inputs['observations'],
            'currency_type_id' => $currency_type_id,
            'total_retention' => $inputs['total_retention'],
            'total' => $inputs['total'],
            'documents' => DocumentInput::set($inputs),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
        ];
    }
}