<?php

namespace App\CoreFacturalo\Formats\Retentions;

use App\CoreFacturalo\Formats\Common\ActionFormat;
use App\CoreFacturalo\Formats\Common\EstablishmentFormat;
use App\CoreFacturalo\Formats\Common\LegendFormat;
use App\CoreFacturalo\Formats\Common\PersonFormat;
use App\CoreFacturalo\Formats\Functions;
use App\CoreFacturalo\Formats\Retentions\Partials\DocumentFormat;
use App\Models\Tenant\Company;
use App\Models\Tenant\Retention;
use Illuminate\Support\Str;

class RetentionFormat
{
    public static function transform($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series_id'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Retention::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        $establishment = EstablishmentFormat::format($inputs['establishment_id']);
        $supplier = PersonFormat::format($inputs['person_id']);
        $legends = LegendFormat::format($inputs);
        $actions = ActionFormat::format($inputs);

        return [
            'type' => 'retention',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
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
            'currency_type_id' => $inputs['currency_type_id'],
            'total_retention' => $inputs['total_retention'],
            'total' => $inputs['total'],
            'documents' => DocumentFormat::format($inputs),
            'legends' => $legends,
            'actions' => $actions,
        ];
    }
}