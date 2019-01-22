<?php

class DocumentTransform
{
    public static function transform($inputs)
    {
        $inputs['series'] = \App\Models\Tenant\Series::find($inputs['series_id']);
    }
}