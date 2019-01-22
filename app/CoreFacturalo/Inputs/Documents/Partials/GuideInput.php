<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class GuideInput
{
    public static function set($inputs)
    {
        if(array_key_exists('guides', $inputs)) {
            if($inputs['guides']) {
                $guides = [];
                foreach ($inputs['guides'] as $row) {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];

                    $guides[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                    ];
                }
                return $guides;
            }
        }
        return null;
    }
}