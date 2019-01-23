<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Inputs\Dispatches\DispatchInput;
use App\CoreFacturalo\Inputs\Documents\DocumentInput;
use App\CoreFacturalo\Inputs\Retentions\RetentionInput;
use App\CoreFacturalo\Inputs\Summaries\SummaryInput;
use App\CoreFacturalo\Inputs\Voided\VoidedInput;
use App\CoreFacturalo\Transforms\Api\Dispatches\DispatchTransform;
use App\CoreFacturalo\Transforms\Api\Documents\DocumentTransform;
use App\CoreFacturalo\Transforms\Api\Retentions\RetentionTransform;
use App\CoreFacturalo\Transforms\Api\Summaries\SummaryTransform;
use App\CoreFacturalo\Transforms\Api\Voided\VoidedTransform;
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
        $transform_inputs = $this->transformInputs($inputs, $type, $service);
//        dd($this->setInputs($transform_inputs, $type, $service));
        $request->replace($this->setInputs($transform_inputs, $type, $service));
        return $next($request);
    }

    private function transformInputs($inputs, $type, $service)
    {
        $class = "App\\CoreFacturalo\\Transforms\\".ucfirst($service)."\\".ucfirst(str_plural($type))."\\".ucfirst($type)."Transform";
        return $class::transform($inputs);
    }

    private function setInputs($inputs, $type, $service)
    {
        $class = "App\\CoreFacturalo\\Inputs\\".ucfirst(str_plural($type))."\\".ucfirst($type)."Input";
        return $class::set($inputs, $service);
    }
}