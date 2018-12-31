<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'document.customer_id' => [
                'required',
            ],
            'document.establishment_id' => [
                'required',
            ],
            'document.series' => [
                'required',
            ],
            'document.date_of_issue' => [
                'required',
            ],
//            'document_base.note_credit_or_debit_type_id' => [
//                'required_if:document.document_type_id,"07", "08"',
//            ],
//            'document_base.note_description' => [
//                'required_if:document.document_type_id,"07", "08"',
//            ],
        ];
    }
}