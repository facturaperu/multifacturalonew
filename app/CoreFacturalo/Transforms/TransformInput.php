<?php

namespace App\CoreFacturalo\Transforms;

use App\CoreFacturalo\Transforms\Inputs\DocumentInput;
use App\CoreFacturalo\Transforms\Inputs\InvoiceInput;
use App\CoreFacturalo\Transforms\Inputs\NoteInput;
use App\CoreFacturalo\Transforms\Inputs\RetentionInput;
use App\CoreFacturalo\Transforms\Inputs\SummaryInput;
use App\CoreFacturalo\Transforms\Inputs\VoidedInput;
use Closure;

class TransformInput
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
    public function handle($request, Closure $next, $type, $apiOrWeb)
    {
        $isWeb = ($apiOrWeb === 'api')?false:true;
        if($type === 'document') {
            $originalAttributes = $this->originalAttributeDocument($request->all(), $isWeb);
        } elseif($type === 'retention') {
            $originalAttributes = $this->originalAttributeRetention($request->all(), $isWeb);
        } elseif ($type === 'summary') {
            $originalAttributes = $this->originalAttributeSummary($request->all(), $isWeb);
        } else {
            $originalAttributes = $this->originalAttributeVoided($request->all(), $isWeb);
        }
        $request->replace($originalAttributes);
        return $next($request);
    }

    private function originalAttributeDocument($inputs, $isWeb)
    {
        $aux_document = DocumentInput::transform($inputs, $isWeb);
        $document = $aux_document['document'];
        if(in_array($document['document_type_id'], ['01', '03'])) {
            $aux_document_base = InvoiceInput::transform($inputs, $document, $isWeb);
        } else {
            $aux_document_base = NoteInput::transform($inputs, $document, $isWeb);
        }
        $document['group_id'] = $aux_document_base['group_id'];

        $original_attributes = [
            'type' => $aux_document_base['type'],
            'document' => $document,
            'document_base' => $aux_document_base['document_base'],
            'actions' => $aux_document['actions'],
            'success' => true,
        ];

        return $original_attributes;
    }

    private function originalAttributeSummary($inputs, $isWeb)
    {
        $original_attributes = SummaryInput::transform($inputs, $isWeb);

        return $original_attributes;
    }

    private function originalAttributeVoided($inputs, $isWeb)
    {
        $original_attributes = VoidedInput::transform($inputs, $isWeb);

        return $original_attributes;
    }

    private function originalAttributeRetention($inputs, $isWeb)
    {
        $original_attributes = RetentionInput::transform($inputs, $isWeb);

        return $original_attributes;
    }
}