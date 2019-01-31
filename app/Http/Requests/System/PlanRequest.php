<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'name' => [
                'required', 
            ],
            'pricing' => [
                'required',
                'numeric' 
            ],
            'limit_users' => [
                'required',
                'numeric'  
            ],
            'limit_documents' => [
                'required',
                'numeric' 
            ],
            'plan_documents' => [
                'required'
            ],
        ];
    }
}