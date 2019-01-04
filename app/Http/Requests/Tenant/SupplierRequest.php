<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'number' => [
                'required',
                Rule::unique('tenant.suppliers')->ignore($id),
            ],
            'name' => [
                'required',
                Rule::unique('tenant.suppliers')->ignore($id),
            ],
            'identity_document_type_id' => [
                'required',
            ],
            'country_id' => [
                'required',
            ],
            'department_id' => [
                'required_if:identity_document_type_id,"06000006"',
            ],
            'province_id' => [
                'required_if:identity_document_type_id,"06000006"',
            ],
            'district_id' => [
                'required_if:identity_document_type_id,"06000006"',
            ],
            'address' => [
                'required_if:identity_document_type_id,"06000006"',
            ],
            'email' => [
                'nullable',
                'email',
            ]
        ];
    }
}