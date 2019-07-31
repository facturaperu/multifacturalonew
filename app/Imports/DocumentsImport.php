<?php

namespace App\Imports;

use App\Models\Tenant\Document;
use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class DocumentsImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row)
            {
                // dd($row[2]);
                $nrodocumento = $row[3];
                $serienumero = explode('-', $nrodocumento);
                $serie = $serienumero[0];
                $number = $serienumero[1];
                $correlativo = (int)$number;

                if($row[2] == '03'){
                    $document_type = '03';
                    $document_type_operation = '0101';
                } elseif($row[2] == '0001'){
                    $document_type = '01';
                    $document_type_operation = '0101';  
                } elseif ($row[2] == '01') {
                    $document_type = '01';
                    $document_type_operation = '0101';
                }else {
                    return 'la serie: '.$serie.' no es valida para documentos electrónicos';
                }

                // dd("row2:".$row[2]."document_type:".$document_type);

                $date_create = date("Y-m-d", strtotime($row[5]));
                // $time_create = date("H:i:s", strtotime($row[4]));
                $date_due = date("Y-m-d", strtotime($row[5])); //verificar

                $currency = ($row[11] == 'S') ? 'PEN' : 'Registre nueva moneda' ;

                //cliente
                $co_number = rtrim($row[9]);
                if ($co_number > 0) {
                    if (strlen($co_number) == 11) {
                        $client_document_type = '6';
                        $company_number = $co_number;
                    } elseif (strlen($co_number) == 8) {
                        $client_document_type = '1';
                        $company_number = $co_number;
                    }
                }
                 else {
                    $client_document_type = '0';
                    $company_number = '00000000'; 
                }
                $company_name = $row[10];
                $company_address = $row[20];

                //totales
                $mtototal = $row[15];
                $mtoimpuesto = $row[13];
                $mtosubtotal = $row[12];

                //unidad de medida
                $cdunimed = $row[22];
                if (rtrim($cdunimed) == 'GLNS') {
                    $unit_type = 'GLL';
                } else {
                    $unit_type = 'NIU';
                }
                


                //genero json y envio a api para no hacer insert 
                
                $json = array(
                    "serie_documento" => $serie,
                    "numero_documento" => $correlativo,
                    "fecha_de_emision" => $date_create,
                    "hora_de_emision" => "11:00:00",
                    "codigo_tipo_operacion" => $document_type_operation,
                    "codigo_tipo_documento" => $document_type,
                    "codigo_tipo_moneda" => $currency,
                    "fecha_de_vencimiento" => $date_due,
                    "numero_orden_de_compra" => "-",
                    "totales" => [
                        "total_exportacion" => 0.00,
                        "total_operaciones_gravadas" => $mtosubtotal,
                        "total_operaciones_inafectas" => 0.00,
                        "total_operaciones_exoneradas" => 0.00,
                        "total_operaciones_gratuitas" => 0.00,
                        "total_igv" => $mtoimpuesto,
                        "total_impuestos" => $mtoimpuesto,
                        "total_valor" => $mtototal,
                        "total_venta" => $mtototal
                    ],
                    "datos_del_emisor" => [
                        "codigo_del_domicilio_fiscal" => "0000"
                    ],
                    "datos_del_cliente_o_receptor" => [
                        "codigo_tipo_documento_identidad" => $client_document_type,
                        "numero_documento" => $company_number,
                        "apellidos_y_nombres_o_razon_social" => rtrim($company_name),
                        "codigo_pais" => "PE",
                        "ubigeo" => "010101",
                        "direccion" => rtrim($company_address),
                        "correo_electronico" => "",
                        "telefono" => ""
                    ],
                    "items" => [
                        [
                            "codigo_interno" => substr($row[21],0,10),
                            "descripcion" => rtrim($row[21]),
                            "codigo_producto_sunat" => "",
                            "unidad_de_medida" => $unit_type,
                            "cantidad" => $row[24],
                            "valor_unitario" => $row[25],
                            "codigo_tipo_precio" => "01",
                            "precio_unitario" => $row[25],
                            "codigo_tipo_afectacion_igv" => "10",
                            "total_base_igv" => $row[14],
                            "porcentaje_igv" => "18",
                            "total_igv" => $mtoimpuesto,
                            "total_impuestos" => $mtoimpuesto,
                            "total_valor_item" => $mtosubtotal,
                            "total_item" => $mtototal,
                            "datos_adicionales" => [
                                [
                                    "codigo" => "5010",
                                    "descripcion" => "Número de Placa",
                                    "valor" => rtrim($row[23]),
                                    "fecha_inicio" => "",
                                    "fecha_fin" => "",
                                    "duracion" => ""
                                ]
                            ]
                        ]
                    ]
                );

                $url = url('/api/documents');
                $token = \Auth::user()->api_token;

                // dd($json);

                try {

                    $client = new \GuzzleHttp\Client();

                    $response = $client->post($url, [
                        'headers' => [
                            'Content-Type' => 'Application/json',
                            'Authorization' => 'Bearer '.$token
                        ],
                        'json' => $json
                    ]);
                } catch (Exception $e) {
                    dd($e);
                }

                $registered += 1;
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
