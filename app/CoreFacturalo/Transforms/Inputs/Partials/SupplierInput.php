<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

use App\Models\Tenant\Supplier;
use Exception;

class SupplierInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $supplier = self::findSupplier($inputs['supplier_id']);
        } else {
            $supplier_inputs = $inputs['datos_del_proveedor'];
            $identity_document_type_id = $supplier_inputs['codigo_tipo_documento_identidad'];
            $number = $supplier_inputs['numero_documento'];
            $name = $supplier_inputs['apellidos_y_nombres_o_razon_social'];
            $trade_name = $supplier_inputs['nombre_comercial'];
            $country_id = (array_key_exists('codigo_pais', $supplier_inputs))?$supplier_inputs['codigo_pais']:'PE';
            $district_id = (array_key_exists('ubigeo', $supplier_inputs))?$supplier_inputs['ubigeo']:null;
            $province_id = ($district_id)?substr($district_id, 0 ,4):null;
            $department_id = ($district_id)?substr($district_id, 0 ,2):null;
            $address = (array_key_exists('direccion', $supplier_inputs))?$supplier_inputs['direccion']:null;
            $email = (array_key_exists('correo_electronico', $supplier_inputs))?$supplier_inputs['correo_electronico']:null;
            $telephone = (array_key_exists('telefono', $supplier_inputs))?$supplier_inputs['telefono']:null;

            $supplier = Supplier::updateOrCreate(
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
            'supplier_id' => $supplier->id,
            'supplier' => [
                'identity_document_type_id' => $supplier->identity_document_type_id,
                'identity_document_type' => [
                    'id' => $supplier->identity_document_type_id,
                    'description' => $supplier->identity_document_type->description,
                ],
                'number' => $supplier->number,
                'name' => $supplier->name,
                'trade_name' => $supplier->trade_name,
                'country_id' => $supplier->country_id,
                'country' => [
                    'id' => $supplier->country_id,
                    'description' => $supplier->country->description,
                ],
                'department_id' => $supplier->department_id,
                'department' => [
                    'id' => $supplier->department_id,
                    'description' => optional($supplier->department)->description,
                ],
                'province_id' => $supplier->province_id,
                'province' => [
                    'id' => $supplier->province_id,
                    'description' => optional($supplier->province)->description,
                ],
                'district_id' => $supplier->district_id,
                'district' => [
                    'id' => $supplier->district_id,
                    'description' => optional($supplier->district)->description,
                ],
                'address' => $supplier->address,
                'email' => $supplier->email,
                'telephone' => $supplier->telephone,
            ]
        ];
    }

    private static function findSupplier($supplier_id)
    {
        if(!$supplier_id) {
            throw new Exception("El proveedor es requerido");
        }

        return Supplier::find($supplier_id);
    }
}