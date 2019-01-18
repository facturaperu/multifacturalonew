<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Transform\DispatchInput;
use App\CoreFacturalo\Transform\DocumentInput;
use App\CoreFacturalo\Transform\RetentionInput;
use App\CoreFacturalo\Transform\SummaryInput;
use App\CoreFacturalo\Transform\VoidedInput;
use Closure;

class Transform
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  $type
     * @param  $service
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next, $type, $service)
    {
        if($type === 'dispatch') {
            $originalAttributes = DispatchInput::transform($request->all(), $service);
        } elseif ($type === 'retention') {
            $originalAttributes = RetentionInput::transform($request->all(), $service);
        } elseif ($type === 'summary') {
            $originalAttributes = SummaryInput::transform($request->all(), $service);
        } elseif ($type === 'voided') {
            $originalAttributes = VoidedInput::transform($request->all(), $service);
        } else {
            $originalAttributes = DocumentInput::transform($request->all(), $service);
        }
        $request->replace($originalAttributes);
        return $next($request);
    }
}