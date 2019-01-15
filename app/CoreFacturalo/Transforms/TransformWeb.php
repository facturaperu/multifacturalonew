<?php

namespace App\CoreFacturalo\Transforms;

use App\CoreFacturalo\Transforms\Api\Web\Documents\SummaryInput;
use App\CoreFacturalo\Transforms\Api\Web\Documents\VoidedInput;
use App\CoreFacturalo\Transforms\Web\Documents\DocumentInput;
use Closure;

class TransformWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $type
     * @param  $apiOrWeb
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if($type === 'document') {
            $originalAttributes = DocumentInput::transform($request->all());
        } elseif($type === 'retention') {
            $originalAttributes = []; //$this->originalAttributeRetention($request->all());
        } elseif ($type === 'summary') {
            $originalAttributes = SummaryInput::transform($request->all());
        } elseif ($type === 'voided') {
            $originalAttributes = VoidedInput::transform($request->all());
        } else {
            $originalAttributes = [];
        }
//        dd($originalAttributes);
        $request->replace($originalAttributes);
        return $next($request);
    }
}