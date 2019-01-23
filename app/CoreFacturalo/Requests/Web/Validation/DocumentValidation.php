<?php

namespace App\CoreFacturalo\Requests\Web\Validation;

class DocumentValidation
{
    public static function validation($inputs)
    {
//        $inputs['establishment_id'] = Functions::establishment($inputs['establishment']);
//        unset($inputs['establishment']);
//
//        Functions::validateSeries($inputs);

        $series = Functions::findSeries($inputs);
        $inputs['series'] = $series->number;
        unset($inputs['series_id']);

//        if(in_array($inputs['document_type_id'], ['07', '08'])) {
//            $document =  Functions::findAffectedDocument($inputs);
//            $inputs['affected_document_id'] = $document->id;
//            unset($inputs['affected_document_external_id']);
//        }

//        dd($inputs);
//        $inputs['customer_id'] = Functions::person($inputs['customer'], 'customer');
//        unset($inputs['customer']);
//
//        $inputs['items'] = self::items($inputs['items']);
//
        return $inputs;
    }

//    private static function items($inputs)
//    {
//        foreach ($inputs as &$row)
//        {
//            $row['item_id'] = Functions::item($row);
//            unset($row['internal_id'], $row['description']);
//            unset($row['item_type_id'], $row['item_code']);
//            unset($row['item_code_gs1'], $row['unit_type_id']);
//            unset($row['currency_type_id']);
//        }
//
//        return $inputs;
//    }
}