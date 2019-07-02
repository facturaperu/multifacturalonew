<?php

namespace Modules\Services\Helpers\Dni;

use GuzzleHttp\Client;
use Modules\Services\Models\Person;

class Essalud
{
    public static function search($number)
    {
        try {
            $parameters = [
                'http_errors' => false,
                'connect_timeout' => 5,
            ];

            $client = new  Client(['base_uri' => 'https://ww1.essalud.gob.pe/sisep/postulante/postulante/']);
            $response = $client->request('GET', 'postulante_obtenerDatosPostulante.htm?strDni='.$number, $parameters);
            if ($response->getStatusCode() == 200 && $response != "") {
                $json = (object)json_decode($response->getBody()->getContents(), true);
                $data_person = $json->DatosPerson[0];
                if (isset($data_person) && count($data_person) > 0 &&
                    strlen($data_person['DNI']) >= 8 && $data_person['Nombres'] !== ''
                ) {
                    $person = new Person();
                    $person->name = $data_person['ApellidoPaterno'] . ' ' . $data_person['ApellidoMaterno'] . ', ' . $data_person['Nombres'];
                    $person->number = $data_person['DNI'];
                    $person->verification_code = Functions::verificationCode($data_person['DNI']);
                    $person->first_name = $data_person['ApellidoPaterno'];
                    $person->last_name = $data_person['ApellidoMaterno'];
                    $person->names = $data_person['Nombres'];
                    $person->date_of_birthday = $data_person['FechaNacimiento'];
                    $person->sex = ((string)$data_person['Sexo'] === '2') ? 'Masculino' : 'Femenino';

                    return [
                        'success' => true,
                        'data' => [
                            'razon_social' => $person->name
                        ]
                    ];
                }
            }
            return [
                'success' => false,
                'message' => 'Datos no encontrados.'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>