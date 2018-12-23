<?php

namespace App\CoreFacturalo\Transforms\Inputs;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class SummaryInput
{
    public static function transform($inputs)
    {
        $soap_type_id = Company::active()->soap_type_id;

        $date_of_issue = $inputs['fecha_de_emision'];
        $date_of_reference = $inputs['fecha_de_referencia'];
        $process_type_id = $inputs['codigo_tipo_proceso'];

        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = self::filename($identifier);
        $documents = self::findDocuments($soap_type_id, $date_of_reference);

        return [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'process_type_id' => $process_type_id,
            'ubl_version' => '2.0',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $date_of_reference,
            'identifier' => $identifier,
            'filename' => $filename,
            'documents' => $documents
        ];
    }

    private static function identifier($soap_type_id, $date_of_issue)
    {
        $summaries = Summary::whereSoapTypeId($soap_type_id)
                            ->where('date_of_issue', $date_of_issue)
                            ->get();
        $numeration = count($summaries) + 1;

        return join('-', ['RC', Carbon::parse($date_of_issue)->format('Ymd'), $numeration]);
    }

    private function filename($identifier)
    {
        $company = Company::active();
        return $company->number.'-'.$identifier;
    }

    private static function findDocuments($soap_type_id, $date_of_reference)
    {
        $documents = Document::whereSoapTypeId('soap_type_id', $soap_type_id)
                        ->where('date_of_issue', $date_of_reference)
                        ->where('group_id', '02')
                        ->get();

        if(count($documents) === 0) {
            throw new Exception("No se encontraron documentos con la fecha {$date_of_reference}");
        }

        return $documents->pluck('id');
    }
}