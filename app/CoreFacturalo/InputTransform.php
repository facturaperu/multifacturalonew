<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Inputs\Documents\DocumentInput;
use App\CoreFacturalo\Inputs\Documents\Partials\DiscountInput;
use App\CoreFacturalo\Inputs\Retentions\RetentionInput;
use App\CoreFacturalo\Inputs\Summaries\SummaryInput;
use App\CoreFacturalo\Inputs\Voided\VoidedInput;
use App\CoreFacturalo\Transforms\Documents\DocumentTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\DiscountTransform;
use App\CoreFacturalo\Transforms\Retentions\RetentionTransform;
use App\CoreFacturalo\Transforms\Summaries\SummaryTransform;
use App\CoreFacturalo\Transforms\Voided\VoidedTransform;
use Closure;

class InputTransform
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
        $request->replace($this->setInputs($inputs, $type, $service));
        return $next($request);
    }

    private function transformInputs($inputs, $type)
    {
        switch ($type) {
            case 'dispatch':
                return DiscountTransform::transform($inputs);
                break;
            case 'retention':
                return RetentionTransform::transform($inputs);
                break;
            case 'summary':
                return SummaryTransform::transform($inputs);
                break;
            case 'voided':
                return VoidedTransform::transform($inputs);
                break;
            default:
                return DocumentTransform::transform($inputs);
                break;
        }
    }

    private function setInputs($inputs, $type, $service)
    {
        switch ($type) {
            case 'dispatch':
                return DiscountInput::set($inputs, $service);
                break;
            case 'retention':
                return RetentionInput::set($inputs, $service);
                break;
            case 'summary':
                return SummaryInput::set($inputs, $service);
                break;
            case 'voided':
                return VoidedInput::set($inputs, $service);
                break;
            default:
                return DocumentInput::set($inputs, $service);
                break;
        }
    }
}