<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Transforms\TransformApi\Documents\DocumentInput as DocumentApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Documents\DocumentInput as DocumentWebInput;
use App\CoreFacturalo\Transforms\TransformApi\Summaries\SummaryInput as SummaryApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Summaries\SummaryInput as SummaryWebInput;
use App\CoreFacturalo\Transforms\TransformApi\Voided\VoidedInput as VoidedApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Voided\VoidedInput as VoidedWebInput;
use App\CoreFacturalo\Transforms\TransformApi\Retentions\RetentionInput as RetentionApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Retentions\RetentionInput as RetentionWebInput;
use App\CoreFacturalo\Transforms\TransformApi\Dispatches\DispatchInput as DispatchApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Dispatches\DispatchInput as DispatchWebInput;

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
        $inputs = $request->all();
        if($service === 'api') {
            $inputs = $this->transformInputs($inputs, $type);
        }
        $request->replace($this->formatInputs($inputs, $type));
        return $next($request);
    }

    private function transformInputs($inputs, $type)
    {
        $transformClass = 'App\\CoreFacturalo\\Transforms\\'.ucfirst($type);

        return $transformClass::transform($inputs);
    }

    private function formatInputs($inputs, $type)
    {
        $formatClass = 'App\\CoreFacturalo\\Formats\\'.ucfirst($type).'Format';

        return $formatClass::format($inputs);
    }
}