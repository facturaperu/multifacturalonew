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
            foreach ($rows as $row)
            {
                $nrodocumento = $row[3];
                $ser_num = substr_replace($nrodocumento, '-', 4, 0);
                $serienumero = explode('-', $ser_num);
                $serie = $serienumero[0];
                $number = $serienumero[1];
                $correlativo = (int)$number;
                $serie_split = str_split($serie);

                if($serie_split[0] === 'B'){
                    $document_type = '03';
                    $document_type_operation = '0101';
                } elseif($serie_split[0] === 'F'){
                    $document_type = '01';
                    $document_type_operation = '0101';                    
                } else {
                    return 'la serie: '.$serie.' no es valida para documentos electrónicos';
                }

                $date_create = date("Y-m-d", strtotime($row[4]));
                $time_create = date("H:i:s", strtotime($row[4]));
                $date_due = date("Y-m-d", strtotime($row[64])); //verificar

                $currency = ($row[16] == 'S') ? 'PEN' : 'Registre nueva moneda' ;

                //cliente
                $co_number = rtrim($row[12]);
                if (strlen($co_number) == 11) {
                    $client_document_type = '6';
                    $company_number = $co_number;
                } elseif (strlen($co_number) == 8) {
                    $client_document_type = '1';
                    $company_number = $co_number;
                } else {
                    $client_document_type = '0';
                    $company_number = '00000000'; 
                }
                $company_name = $row[13];
                $company_address = $row[120];

                //totales
                $mtototal = $row[28];
                $mtoimpuesto = $row[27];
                $mtosubtotal = $row[25];

                //unidad de medida
                $cdunimed = $row[215];
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
                    "hora_de_emision" => $time_create,
                    "codigo_tipo_operacion" => $document_type_operation,
                    "codigo_tipo_documento" => $document_type,
                    "codigo_tipo_moneda" => $currency,
                    "fecha_de_vencimiento" => $date_due,
                    "numero_orden_de_compra" => $row[64],
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
                            "codigo_interno" => $row[82],
                            "descripcion" => rtrim($row[214]),
                            "codigo_producto_sunat" => "",
                            "unidad_de_medida" => $unit_type,
                            "cantidad" => $row[91],
                            "valor_unitario" => $row[93],
                            "codigo_tipo_precio" => "01",
                            "precio_unitario" => $row[93],
                            "codigo_tipo_afectacion_igv" => "10",
                            "total_base_igv" => $row[98],
                            "porcentaje_igv" => $row[86],
                            "total_igv" => $row[100],
                            "total_impuestos" => $row[100],
                            "total_valor_item" => $row[98],
                            "total_item" => $row[101],
                            "datos_adicionales" => [
                                [
                                    "codigo" => "5010",
                                    "descripcion" => "Número de Placa",
                                    "valor" => rtrim($row[31]),
                                    "fecha_inicio" => "",
                                    "fecha_fin" => "",
                                    "duracion" => ""
                                ]
                            ]
                        ]
                    ],
                    "acciones" => [
                        "enviar_xml_firmado" => false
                    ]
                );

                $url = url('/api/documents');
                $token = \Auth::user()->api_token;

                // dd(json_encode($json));

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


                // $description = $row[0];
                // $item_type_id = '01';
                // $internal_id = ($row[1])?:null;
                // $item_code = ($row[2])?:null;
                // $unit_type_id = $row[3];
                // $currency_type_id = $row[4];
                // $sale_unit_price = $row[5];
                // $sale_affectation_igv_type_id = $row[6];
                // $has_igv = (strtoupper($row[7]) === 'SI')?true:false;
                // $purchase_unit_price = ($row[8])?:0;
                // $purchase_affectation_igv_type_id = ($row[9])?:null;
                // $stock = $row[10];
                // $stock_min = $row[11];

                // if($internal_id) {
                //     $item = Item::where('internal_id', $internal_id)
                //                     ->first();
                // } else {
                //     $item = null;
                // }

                // $establishment_id = auth()->user()->establishment->id;
                // $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

                // if(!$item) {
                //     Item::create([
                //         'description' => $description,
                //         'item_type_id' => $item_type_id,
                //         'internal_id' => $internal_id,
                //         'item_code' => $item_code,
                //         'unit_type_id' => $unit_type_id,
                //         'currency_type_id' => $currency_type_id,
                //         'sale_unit_price' => $sale_unit_price,
                //         'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                //         'has_igv' => $has_igv,
                //         'purchase_unit_price' => $purchase_unit_price,
                //         'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                //         'stock' => $stock,
                //         'stock_min' => $stock_min,
                //         // 'warehouse_id' => $warehouse->id
                //     ]);
                //     $registered += 1;
                // }
                $registered += 1;
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
