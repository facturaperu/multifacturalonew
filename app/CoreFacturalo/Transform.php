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
        if($type === 'document') {
            $originalAttributes = $this->documentInputTransform($request->all(), $service);
        } elseif ($type === 'summary') {
            $originalAttributes = $this->summaryInputTransform($request->all(), $service);
        } elseif ($type === 'voided') {
            $originalAttributes = $this->voidedInputTransform($request->all(), $service);
        } elseif ($type === 'retention') {
            $originalAttributes = $this->retentionInputTransform($request->all(), $service);
        } else {
            $originalAttributes = $this->dispatchInputTransform($request->all(), $service);
        }
        $request->replace($originalAttributes);
        return $next($request);
    }

    private function documentInputTransform($inputs, $service)
    {
        if($service === 'api') {
            return DocumentApiInput::transform($inputs);
        }
        return DocumentWebInput::transform($inputs);
    }

    private function summaryInputTransform($inputs, $service)
    {
        if($service === 'api') {
            return SummaryApiInput::transform($inputs);
        }
        return SummaryWebInput::transform($inputs);
    }

    private function voidedInputTransform($inputs, $service)
    {
        if($service === 'api') {
            return VoidedApiInput::transform($inputs);
        }
        return VoideWebInput::transform($inputs);
    }

    private function retentionInputTransform($inputs, $service)
    {
        if($service === 'api') {
            return RetentionApiInput::transform($inputs);
        }
        return RetentionWebInput::transform($inputs);
    }

    private function dispatchInputTransform($inputs, $service)
    {
        if($service === 'api') {
            return DispatchApiInput::transform($inputs);
        }
        return DispatchWebInput::transform($inputs);
    }
}