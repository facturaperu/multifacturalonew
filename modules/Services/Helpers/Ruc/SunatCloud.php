<?php

namespace Modules\Services\Helpers\Ruc;

use Exception;
use GuzzleHttp\Client;

class SunatCloud
{
    public static function search($number)
    {
        try {
            $client = new  Client(['base_uri' => 'https://api.sunat.cloud']);
            $response = $client->request('GET', 'ruc/'.$number);

            if ($response->getStatusCode() == 200 && $response != "") {
                $data_company = json_decode($response->getBody()->getContents(), true);
                if (!is_null($data_company)) {

                    $res = null;
                    if($data_company['domicilio_fiscal'] !== '-') {
                        $res = Functions::get_location_by_address($data_company['domicilio_fiscal']);
                    }

                    $data_company['razon_social'] = Functions::get_name($data_company['razon_social']);

                    $company = new CompanyModel();
                    $company->ruc = $data_company['ruc'];
                    $company->razon_social = $data_company['razon_social'];
                    $company->nombre_comercial = $data_company['nombre_comercial'];
                    $company->contribuyente_condicion = $data_company['contribuyente_condicion'];
                    $company->contribuyente_tipo = $data_company['contribuyente_tipo'];
                    $company->contribuyente_estado = $data_company['contribuyente_estado'];
                    $company->direccion = ($res)?$res['address']:null;
                    $company->departamento = ($res)?$res['department']:null;
                    $company->provincia = ($res)?$res['province']:null;
                    $company->distrito = ($res)?$res['district']:null;
                    $company->ubigeo = ($res)?$res['location_id']:[];
                    $company->fecha_inscripcion = $data_company['fecha_inscripcion'];
                    $company->sistema_emision = $data_company['sistema_emision'];
                    $company->sistema_contabilidad = $data_company['sistema_contabilidad'];
                    $company->emision_electronica = $data_company['emision_electronica'];
                    $company->fecha_inscripcion_ple = $data_company['fecha_inscripcion_ple'];
                    $company->fecha_baja = $data_company['fecha_baja'];
                    $company->representante_legal = $data_company['representante_legal'];
                    $company->ciiu = $data_company['ciiu'];
                    $company->fecha_actividad = $data_company['fecha_actividad'];
                    $company->locales = $data_company['locales'];

                    return [
                        'success' => true,
                        'data' => $company
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Datos no encontrados.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Ocurrio un error inesperado.'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>