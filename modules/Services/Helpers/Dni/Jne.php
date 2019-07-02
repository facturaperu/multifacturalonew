<?php

namespace Modules\Services\Helpers\Dni;

use GuzzleHttp\Client;
use Modules\Services\Models\Person;

class Jne
{
    public static function search($number)
    {
        try {

            $parameters = [
                'http_errors' => false,
                'connect_timeout' => 5,
            ];

            $client = new  Client(['base_uri' => 'http://aplicaciones007.jne.gob.pe/']);
            $response = $client->request('GET', 'srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI='.$number, $parameters);
            if ($response->getStatusCode() == 200 && $response != "") {
                $text = $response->getBody()->getContents();
                $parts = explode('|', $text);
                if (count($parts) === 3) {
                    $person = new Person();
                    $person->number = $number;
                    $person->verification_code = Functions::verificationCode($number);
                    $person->name = $parts[0].' '.$parts[1].', '.$parts[2];
                    $person->first_name = $parts[0];
                    $person->last_name = $parts[1];
                    $person->names = $parts[2];



                    return [
                        'success' => true,
                        'data' => [
                            'razon_social' => $person->name
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Datos no encontrados.'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Coneccion fallida.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}