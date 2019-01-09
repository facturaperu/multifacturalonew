<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

use App\Models\Tenant\Customer;
use Exception;

class CustomerInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $customer = self::findCustomer($inputs['customer_id']);
        } else {
            $customer_inputs = $inputs['datos_del_cliente_o_receptor'];

            $identity_document_type_id = $customer_inputs['codigo_tipo_documento_identidad'];
            $number = $customer_inputs['numero_documento'];
            $name = $customer_inputs['apellidos_y_nombres_o_razon_social'];
            $trade_name = (array_key_exists('nombre_comercial', $customer_inputs))?$customer_inputs['nombre_comercial']:null;
            $country_id = (array_key_exists('codigo_pais', $customer_inputs))?$customer_inputs['codigo_pais']:'PE';
            $district_id = (array_key_exists('ubigeo', $customer_inputs))?$customer_inputs['ubigeo']:null;
            $province_id = ($district_id)?substr($district_id, 0 ,4):null;
            $department_id = ($district_id)?substr($district_id, 0 ,2):null;
            $address = (array_key_exists('direccion', $customer_inputs))?$customer_inputs['direccion']:null;
            $email = (array_key_exists('correo_electronico', $customer_inputs))?$customer_inputs['correo_electronico']:null;
            $telephone = (array_key_exists('telefono', $customer_inputs))?$customer_inputs['telefono']:null;

            $customer = Customer::updateOrCreate(
                [
                    'identity_document_type_id' => $identity_document_type_id,
                    'number' => $number
                ],
                [
                    'name' => $name,
                    'trade_name' => $trade_name,
                    'country_id' => $country_id,
                    'district_id' => $district_id,
                    'province_id' => $province_id,
                    'department_id' => $department_id,
                    'address' => $address,
                    'email' => $email,
                    'telephone' => $telephone,
                ]
            );
        }

        return [
            'customer_id' => $customer->id,
            'customer' => [
                'identity_document_type_id' => $customer->identity_document_type_id,
                'identity_document_type' => [
                    'id' => $customer->identity_document_type_id,
                    'description' => $customer->identity_document_type->description,
                ],
                'number' => $customer->number,
                'name' => $customer->name,
                'trade_name' => $customer->trade_name,
                'country_id' => $customer->country_id,
                'country' => [
                    'id' => $customer->country_id,
                    'description' => $customer->country->description,
                ],
                'department_id' => $customer->department_id,
                'department' => [
                    'id' => $customer->department_id,
                    'description' => optional($customer->department)->description,
                ],
                'province_id' => $customer->province_id,
                'province' => [
                    'id' => $customer->province_id,
                    'description' => optional($customer->province)->description,
                ],
                'district_id' => $customer->district_id,
                'district' => [
                    'id' => $customer->district_id,
                    'description' => optional($customer->district)->description,
                ],
                'address' => $customer->address,
                'email' => $customer->email,
                'telephone' => $customer->telephone,
            ]
        ];
    }

    private static function findCustomer($customer_id)
    {
        if(!$customer_id) {
            throw new Exception("El cliente es requerido");
        }

        return Customer::find($customer_id);
    }
}