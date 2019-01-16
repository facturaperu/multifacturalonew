<?php

namespace App\CoreFacturalo\Transforms;

use App\CoreFacturalo\Transforms\TransWeb\Documents\DocumentInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\SummaryInput;
use App\CoreFacturalo\Transforms\TransWeb\Documents\VoidedInput;
use Closure;

class TransformWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $type
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

        $request->replace($originalAttributes);
        return $next($request);
    }
}