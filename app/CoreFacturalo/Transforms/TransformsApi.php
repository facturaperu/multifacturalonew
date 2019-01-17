<?php

namespace App\CoreFacturalo\Transforms;

use App\CoreFacturalo\Transforms\TransApi\Dispatches\DispatchInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\DocumentInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\SummaryInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\VoidedInput;
use App\CoreFacturalo\Transforms\TransApi\Retentions\RetentionInput;
use Closure;

class TransformsApi
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
            $originalAttributes = RetentionInput::transform($request->all());
        } elseif($type === 'dispatch') {
            $originalAttributes = DispatchInput::transform($request->all());
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