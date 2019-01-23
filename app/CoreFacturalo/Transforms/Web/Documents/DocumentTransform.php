<?php

namespace App\CoreFacturalo\Transforms\Web\Documents;

use App\Models\Tenant\Series;

class DocumentTransform
{
    public static function transform($inputs)
    {
        $inputs['series'] = self::findSeries($inputs['series_id']);
        $inputs['invoice'] = self::setInvoice($inputs);
        $inputs['note'] = self::setNote($inputs);

        unset($inputs['series_id']);
        return $inputs;
    }

    private static function findSeries($series_id)
    {
        $series = Series::find($series_id);
        return $series->number;
    }

    private static function setInvoice($inputs)
    {
        if(in_array($inputs['document_type_id'], ['01', '03'])) {
            return [
                'operation_type_id' => $inputs['operation_type_id'],
                'date_of_due' => $inputs['date_of_due'],
            ];
        }
        return null;
    }

    private static function setNote($inputs)
    {
        if(in_array($inputs['document_type_id'], ['07', '08'])) {
            return [
                'note_credit_or_debit_type_id' => $inputs['note_credit_or_debit_type_id'],
                'note_description' => $inputs['note_description'],
                'affected_document_id' => $inputs['affected_document_id'],
            ];
        }
        return null;
    }
}