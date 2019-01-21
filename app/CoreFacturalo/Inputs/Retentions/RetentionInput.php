<?php

namespace App\CoreFacturalo\Inputs\Retentions;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Inputs\Common\LegendInput;
use App\CoreFacturalo\Inputs\Common\PersonInput;
use App\CoreFacturalo\Inputs\Functions;
use App\CoreFacturalo\Inputs\Retentions\Partials\DocumentInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Retention;
use Illuminate\Support\Str;

class RetentionInput
{
    public static function set($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series_id'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Retention::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        return [
            'type' => 'retention',
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
            'supplier_id' => $inputs['supplier_id'],
            'supplier' => PersonInput::set($inputs['person_id']),
            'retention_type_id' => $inputs['retention_type_id'],
            'observations' => $inputs['observations'],
            'currency_type_id' => $inputs['currency_type_id'],
            'total_retention' => $inputs['total_retention'],
            'total' => $inputs['total'],
            'documents' => DocumentInput::set($inputs),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
        ];
    }
}