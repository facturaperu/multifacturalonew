<?php

namespace Modules\Services\Helpers\Dni;

use GuzzleHttp\Client;
use Modules\Services\Models\Person;

class ReniecCloud
{
    public static function search($number)
    {
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
        ];

        $client = new  Client(['base_uri' => 'https://api.reniec.cloud']);
        $response = $client->request('GET', 'dni/'.$number, $parameters);
        if ($response->getStatusCode() == 200 && $response != "") {
            $data_person = json_decode($response->getBody()->getContents(), true);
//            dd($data_person);
            if (!is_null($data_person) && in_array('apellido_paterno', $data_person)) {
                $person = new Person();
                $person->name = $data_person['apellido_paterno'].' '.$data_person['apellido_materno'].', '.$data_person['nombres'];
                $person->number = $data_person['dni'];
                $person->verification_code = Functions::verificationCode($number);;
                $person->first_name = $data_person['apellido_paterno'];
                $person->last_name = $data_person['apellido_materno'];
                $person->names = $data_person['nombres'];

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
    }
}
?>