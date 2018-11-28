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
            'customer_id' => [
                'required',
            ],
            'establishment_id' => [
                'required',
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'note_type_code' => [
                'required_if:document_type_code,"07", "08"',
            ],
            'description' => [
                'required_if:document_type_code,"07", "08"',
            ],
        ];
    }
}