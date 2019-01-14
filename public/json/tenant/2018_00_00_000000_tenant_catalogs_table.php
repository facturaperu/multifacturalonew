<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
        });

        DB::table('catalogs')->insert([
            ['id' => '01', 'description' => 'Código de tipo de documento'],
            ['id' => '02', 'description' => 'Código de tipo de monedas'],
            ['id' => '03', 'description' => 'Código de tipo de unidad de medida comercial'],
            ['id' => '04', 'description' => 'Código de país'],
            ['id' => '05', 'description' => 'Código de tipos de tributos y otros conceptos'],
            ['id' => '06', 'description' => 'Código de tipo de documento de identidad'],
            ['id' => '07', 'description' => 'Código de tipo de afectación del IGV'],
            ['id' => '08', 'description' => 'Código de tipos de sistema de cálculo del ISC'],
            ['id' => '09', 'description' => 'Códigos de tipo de nota de crédito electrónica'],
            ['id' => '10', 'description' => 'Códigos de tipo de nota de débito electrónica'],
            ['id' => '11', 'description' => 'Códigos de tipo de valor de venta (Resumen diario de boletas y notas)'],
            ['id' => '12', 'description' => 'Código de documentos relacionados tributarios'],
            ['id' => '13', 'description' => 'Código de ubicación geográfica (UBIGEO)'],
            ['id' => '14', 'description' => 'Código de otros conceptos tributarios'],
            ['id' => '15', 'description' => 'Códigos de elementos adicionales en la factura y boleta electrónica'],
            ['id' => '16', 'description' => 'Código de tipo de precio de venta unitario'],
            ['id' => '17', 'description' => 'Código de tipo de operación'],
            ['id' => '18', 'description' => 'Código de modalidad de transporte'],
            ['id' => '19', 'description' => 'Código de estado del ítem (resumen diario)'],
            ['id' => '20', 'description' => 'Código de motivo de traslado'],
            ['id' => '21', 'description' => 'Código de documentos relacionados (sólo guía de remisión electrónica)'],
            ['id' => '22', 'description' => 'Código de regimen de percepciones'],
            ['id' => '23', 'description' => 'Código de regimen de retenciones'],
            ['id' => '25', 'description' => 'Código de producto SUNAT'],
            ['id' => '51', 'description' => 'Código de tipo de operación'],
            ['id' => '52', 'description' => 'Códigos de leyendas'],
            ['id' => '53', 'description' => 'Códigos de cargos o descuentos'],
            ['id' => '54', 'description' => 'Códigos de bienes y servicios sujetos a detracciones'],
            ['id' => '55', 'description' => 'Código de identificación del concepto tributario'],
            ['id' => '59', 'description' => 'Medios de Pago'],
        ]);

        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('catalog_id', 2);
            $table->string('code');
            $table->string('description');
            $table->string('short')->nullable();
            $table->string('symbol')->nullable();
            $table->boolean('exportation')->nullable();
            $table->boolean('free')->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->boolean('base')->nullable();
            $table->enum('type', ['discount', 'charge'])->nullable();
            $table->enum('level', ['item', 'global'])->nullable();
            $table->boolean('active');
        });

        DB::table('codes')->insert([
            ['catalog_id' => '01', 'code' => '01', 'active' => true,  'short' => 'FT', 'description' => 'FACTURA ELECTRÓNICA'],
            ['catalog_id' => '01', 'code' => '03', 'active' => true,  'short' => 'BV', 'description' => 'BOLETA DE VENTA ELECTRÓNICA'],
            ['catalog_id' => '01', 'code' => '07', 'active' => true,  'short' => 'NC', 'description' => 'NOTA DE CRÉDITO'],
            ['catalog_id' => '01', 'code' => '08', 'active' => true,  'short' => 'ND', 'description' => 'NOTA DE DÉBITO'],
            ['catalog_id' => '01', 'code' => '09', 'active' => true,  'short' => null, 'description' => 'Guia de remisión remitente'],
            ['catalog_id' => '01', 'code' => '20', 'active' => true,  'short' => null, 'description' => 'COMPROBANTE DE RETENCIÓN ELECTRÓNICA'],
            ['catalog_id' => '01', 'code' => '31', 'active' => true,  'short' => null, 'description' => 'Guía de remisión transportista'],
            ['catalog_id' => '01', 'code' => '40', 'active' => true,  'short' => null, 'description' => 'COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA'],
            ['catalog_id' => '01', 'code' => '71', 'active' => false, 'short' => null, 'description' => 'Guia de remisión remitente complementaria'],
            ['catalog_id' => '01', 'code' => '72', 'active' => false, 'short' => null, 'description' => 'Guia de remisión transportista complementaria'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '02', 'code' => 'PEN', 'active' => true, 'symbol' => 'S/', 'description' => 'Soles'],
            ['catalog_id' => '02', 'code' => 'USD', 'active' => true, 'symbol' => '$',  'description' => 'Dólares Americanos'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '03', 'code' => 'ZZ',  'active' => true, 'symbol' => null, 'description' => 'Servicio'],
            ['catalog_id' => '03', 'code' => 'BX',  'active' => true, 'symbol' => null, 'description' => 'Caja'],
            ['catalog_id' => '03', 'code' => 'GLL', 'active' => true, 'symbol' => null, 'description' => 'Galones'],
            ['catalog_id' => '03', 'code' => 'GRM', 'active' => true, 'symbol' => null, 'description' => 'Gramos'],
            ['catalog_id' => '03', 'code' => 'KGM', 'active' => true, 'symbol' => null, 'description' => 'Kilos'],
            ['catalog_id' => '03', 'code' => 'LTR', 'active' => true, 'symbol' => null, 'description' => 'Litros'],
            ['catalog_id' => '03', 'code' => 'MTR', 'active' => true, 'symbol' => null, 'description' => 'Metros'],
            ['catalog_id' => '03', 'code' => 'FOT', 'active' => true, 'symbol' => null, 'description' => 'Pies'],
            ['catalog_id' => '03', 'code' => 'INH', 'active' => true, 'symbol' => null, 'description' => 'Pulgadas'],
            ['catalog_id' => '03', 'code' => 'NIU', 'active' => true, 'symbol' => null, 'description' => 'Unidades'],
            ['catalog_id' => '03', 'code' => 'YRD', 'active' => true, 'symbol' => null, 'description' => 'Yardas'],
            ['catalog_id' => '03', 'code' => 'HUR', 'active' => true, 'symbol' => null, 'description' => 'Hora'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '06', 'code' => '0', 'active' => true,  'description' => 'Doc.trib.no.dom.sin.ruc'],
            ['catalog_id' => '06', 'code' => '1', 'active' => true,  'description' => 'DNI'],
            ['catalog_id' => '06', 'code' => '4', 'active' => true,  'description' => 'CE'],
            ['catalog_id' => '06', 'code' => '6', 'active' => true,  'description' => 'RUC'],
            ['catalog_id' => '06', 'code' => '7', 'active' => true,  'description' => 'Pasaporte'],
            ['catalog_id' => '06', 'code' => 'A', 'active' => false, 'description' => 'Ced. Diplomática de identidad'],
            ['catalog_id' => '06', 'code' => 'B', 'active' => false, 'description' => 'Documento identidad país residencia-no.d'],
            ['catalog_id' => '06', 'code' => 'C', 'active' => false, 'description' => 'Tax Identification Number - TIN – Doc Trib PP.NN'],
            ['catalog_id' => '06', 'code' => 'D', 'active' => false, 'description' => 'Identification Number - IN – Doc Trib PP. JJ'],
            ['catalog_id' => '06', 'code' => 'E', 'active' => false, 'description' => 'TAM- Tarjeta Andina de Migración'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '07', 'code' => '10', 'active' => true,  'exportation' => false, 'free' => false, 'description' => 'Gravado - Operación Onerosa'],
            ['catalog_id' => '07', 'code' => '11', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Retiro por premio'],
            ['catalog_id' => '07', 'code' => '12', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Retiro por donación'],
            ['catalog_id' => '07', 'code' => '13', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Retiro'],
            ['catalog_id' => '07', 'code' => '14', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Retiro por publicidad'],
            ['catalog_id' => '07', 'code' => '15', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Bonificaciones'],
            ['catalog_id' => '07', 'code' => '16', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Gravado – Retiro por entrega a trabajadores'],
            ['catalog_id' => '07', 'code' => '17', 'active' => false, 'exportation' => false, 'free' => true,  'description' => 'Gravado – IVAP'],
            ['catalog_id' => '07', 'code' => '20', 'active' => true,  'exportation' => false, 'free' => false, 'description' => 'Exonerado - Operación Onerosa'],
            ['catalog_id' => '07', 'code' => '21', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Exonerado – Transferencia Gratuita'],
            ['catalog_id' => '07', 'code' => '30', 'active' => true,  'exportation' => false, 'free' => false, 'description' => 'Inafecto - Operación Onerosa'],
            ['catalog_id' => '07', 'code' => '31', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto – Retiro por Bonificación'],
            ['catalog_id' => '07', 'code' => '32', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto – Retiro'],
            ['catalog_id' => '07', 'code' => '33', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto – Retiro por Muestras Médicas'],
            ['catalog_id' => '07', 'code' => '34', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto - Retiro por Convenio Colectivo'],
            ['catalog_id' => '07', 'code' => '35', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto – Retiro por premio'],
            ['catalog_id' => '07', 'code' => '36', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto - Retiro por publicidad'],
            ['catalog_id' => '07', 'code' => '37', 'active' => true,  'exportation' => false, 'free' => true,  'description' => 'Inafecto - Transferencia gratuita'],
            ['catalog_id' => '07', 'code' => '40', 'active' => true,  'exportation' => true,  'free' => false, 'description' => 'Exportación de bienes o servicios'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '08', 'code' => '01', 'active' => true, 'description' => 'Sistema al valor'],
            ['catalog_id' => '08', 'code' => '02', 'active' => true, 'description' => 'Aplicación del Monto Fijo'],
            ['catalog_id' => '08', 'code' => '03', 'active' => true, 'description' => 'Sistema de Precios de Venta al Público'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '09', 'code' => '01', 'active' => true, 'description' => 'Anulación de la operación'],
            ['catalog_id' => '09', 'code' => '02', 'active' => true, 'description' => 'Anulación por error en el RUC'],
            ['catalog_id' => '09', 'code' => '03', 'active' => true, 'description' => 'Corrección por error en la descripción'],
            ['catalog_id' => '09', 'code' => '04', 'active' => true, 'description' => 'Descuento global'],
            ['catalog_id' => '09', 'code' => '05', 'active' => true, 'description' => 'Descuento por ítem'],
            ['catalog_id' => '09', 'code' => '06', 'active' => true, 'description' => 'Devolución total'],
            ['catalog_id' => '09', 'code' => '07', 'active' => true, 'description' => 'Devolución por ítem'],
            ['catalog_id' => '09', 'code' => '08', 'active' => true, 'description' => 'Bonificación'],
            ['catalog_id' => '09', 'code' => '09', 'active' => true, 'description' => 'Disminución en el valor'],
            ['catalog_id' => '09', 'code' => '10', 'active' => true, 'description' => 'Otros Conceptos'],
            ['catalog_id' => '09', 'code' => '11', 'active' => true, 'description' => 'Ajustes de operaciones de exportación'],
            ['catalog_id' => '09', 'code' => '12', 'active' => true, 'description' => 'Ajustes afectos al IVAP'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '10', 'code' => '01', 'active' => true, 'description' => 'Intereses por mora'],
            ['catalog_id' => '10', 'code' => '02', 'active' => true, 'description' => 'Aumento en el valor'],
            ['catalog_id' => '10', 'code' => '03', 'active' => true, 'description' => 'Penalidades/ otros conceptos'],
            ['catalog_id' => '10', 'code' => '10', 'active' => true, 'description' => 'Ajustes de operaciones de exportación'],
            ['catalog_id' => '10', 'code' => '11', 'active' => true, 'description' => 'Ajustes afectos al IVAP'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '12', 'code' => '01', 'active' => true, 'description' => 'Factura – emitida para corregir error en el RUC'],
            ['catalog_id' => '12', 'code' => '02', 'active' => true, 'description' => 'Factura – emitida por anticipos'],
            ['catalog_id' => '12', 'code' => '03', 'active' => true, 'description' => 'Boleta de Venta – emitida por anticipos'],
            ['catalog_id' => '12', 'code' => '04', 'active' => true, 'description' => 'Ticket de Salida - ENAPU'],
            ['catalog_id' => '12', 'code' => '05', 'active' => true, 'description' => 'Código SCOP'],
            ['catalog_id' => '12', 'code' => '99', 'active' => true, 'description' => 'Otros'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '14', 'code' => '1000', 'active' => true, 'description' => 'Total valor de venta - operaciones exportadas'],
            ['catalog_id' => '14', 'code' => '1001', 'active' => true, 'description' => 'Total valor de venta - operaciones gravadas'],
            ['catalog_id' => '14', 'code' => '1002', 'active' => true, 'description' => 'Total valor de venta - operaciones inafectas'],
            ['catalog_id' => '14', 'code' => '1003', 'active' => true, 'description' => 'Total valor de venta - operaciones exoneradas'],
            ['catalog_id' => '14', 'code' => '1004', 'active' => true, 'description' => 'Total valor de venta – Operaciones gratuitas'],
            ['catalog_id' => '14', 'code' => '1005', 'active' => true, 'description' => 'Sub total de venta'],
            ['catalog_id' => '14', 'code' => '2001', 'active' => true, 'description' => 'Percepciones'],
            ['catalog_id' => '14', 'code' => '2002', 'active' => true, 'description' => 'Retenciones'],
            ['catalog_id' => '14', 'code' => '2003', 'active' => true, 'description' => 'Detracciones'],
            ['catalog_id' => '14', 'code' => '2004', 'active' => true, 'description' => 'Bonificaciones'],
            ['catalog_id' => '14', 'code' => '2005', 'active' => true, 'description' => 'Total descuentos'],
            ['catalog_id' => '14', 'code' => '3001', 'active' => true, 'description' => 'FISE (Ley 29852) Fondo Inclusión Social Energético'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '15', 'code' => '1000', 'active' => true, 'description' => 'Monto en Letras'],
            ['catalog_id' => '15', 'code' => '1002', 'active' => true, 'description' => 'Leyenda "TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE"'],
            ['catalog_id' => '15', 'code' => '2000', 'active' => true, 'description' => 'Leyenda “COMPROBANTE DE PERCEPCIÓN”'],
            ['catalog_id' => '15', 'code' => '2001', 'active' => true, 'description' => 'Leyenda “BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA"'],
            ['catalog_id' => '15', 'code' => '2002', 'active' => true, 'description' => 'Leyenda “SERVICIOS PRESTADOS EN LA AMAZONÍA  REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA”'],
            ['catalog_id' => '15', 'code' => '2003', 'active' => true, 'description' => 'Leyenda “CONTRATOS DE CONSTRUCCIÓN EJECUTADOS  EN LA AMAZONÍA REGIÓN SELVA”'],
            ['catalog_id' => '15', 'code' => '2004', 'active' => true, 'description' => 'Leyenda “Agencia de Viaje - Paquete turístico”'],
            ['catalog_id' => '15', 'code' => '2005', 'active' => true, 'description' => 'Leyenda “Venta realizada por emisor itinerante”'],
            ['catalog_id' => '15', 'code' => '2006', 'active' => true, 'description' => 'Leyenda: Operación sujeta a detracción'],
            ['catalog_id' => '15', 'code' => '2007', 'active' => true, 'description' => 'Leyenda: Operación sujeta a IVAP'],
            ['catalog_id' => '15', 'code' => '2010', 'active' => true, 'description' => 'Restitución Simplificado de Derechos Arancelarios'],
            ['catalog_id' => '15', 'code' => '3000', 'active' => true, 'description' => 'Detracciones: CODIGO DE BB Y SS SUJETOS A DETRACCION'],
            ['catalog_id' => '15', 'code' => '3001', 'active' => true, 'description' => 'Detracciones: NUMERO DE CTA EN EL BN'],
            ['catalog_id' => '15', 'code' => '3002', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Nombre y matrícula de la embarcación'],
            ['catalog_id' => '15', 'code' => '3003', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Tipo y cantidad de especie vendida'],
            ['catalog_id' => '15', 'code' => '3004', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos -Lugar de descarga'],
            ['catalog_id' => '15', 'code' => '3005', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos -Fecha de descarga'],
            ['catalog_id' => '15', 'code' => '3006', 'active' => true, 'description' => 'Detracciones: Transporte Bienes vía terrestre – Numero Registro MTC'],
            ['catalog_id' => '15', 'code' => '3007', 'active' => true, 'description' => 'Detracciones: Transporte Bienes vía terrestre – configuración vehicular'],
            ['catalog_id' => '15', 'code' => '3008', 'active' => true, 'description' => 'Detracciones: Transporte Bienes vía terrestre – punto de origen'],
            ['catalog_id' => '15', 'code' => '3009', 'active' => true, 'description' => 'Detracciones: Transporte Bienes vía terrestre – punto destino'],
            ['catalog_id' => '15', 'code' => '3010', 'active' => true, 'description' => 'Detracciones: Transporte Bienes vía terrestre – valor referencial preliminar'],
            ['catalog_id' => '15', 'code' => '4000', 'active' => true, 'description' => 'Beneficio hospedajes: Código País de emisión del pasaporte'],
            ['catalog_id' => '15', 'code' => '4001', 'active' => true, 'description' => 'Beneficio hospedajes: Código País de residencia del sujeto no domiciliado'],
            ['catalog_id' => '15', 'code' => '4002', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de ingreso al país'],
            ['catalog_id' => '15', 'code' => '4003', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de ingreso al establecimiento'],
            ['catalog_id' => '15', 'code' => '4004', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de salida del establecimiento'],
            ['catalog_id' => '15', 'code' => '4005', 'active' => true, 'description' => 'Beneficio Hospedajes: Número de días de permanencia'],
            ['catalog_id' => '15', 'code' => '4006', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de consumo'],
            ['catalog_id' => '15', 'code' => '4007', 'active' => true, 'description' => 'Beneficio Hospedajes: Paquete turístico - Nombres y Apellidos del Huésped'],
            ['catalog_id' => '15', 'code' => '4008', 'active' => true, 'description' => 'Beneficio Hospedajes: Paquete turístico – Tipo documento identidad del huésped'],
            ['catalog_id' => '15', 'code' => '4009', 'active' => true, 'description' => 'Beneficio Hospedajes: Paquete turístico – Numero de documento identidad de huésped'],
            ['catalog_id' => '15', 'code' => '5000', 'active' => true, 'description' => 'Proveedores Estado: Número de Expediente'],
            ['catalog_id' => '15', 'code' => '5001', 'active' => true, 'description' => 'Proveedores Estado: Código de unidad ejecutora'],
            ['catalog_id' => '15', 'code' => '5002', 'active' => true, 'description' => 'Proveedores Estado: N° de proceso de selección'],
            ['catalog_id' => '15', 'code' => '5003', 'active' => true, 'description' => 'Proveedores Estado: N° de contrato'],
            ['catalog_id' => '15', 'code' => '6000', 'active' => true, 'description' => 'Comercialización de Oro: Código Unico Concesión Minera'],
            ['catalog_id' => '15', 'code' => '6001', 'active' => true, 'description' => 'Comercialización de Oro: N° declaración compromiso'],
            ['catalog_id' => '15', 'code' => '6002', 'active' => true, 'description' => 'Comercialización de Oro: N° Reg. Especial .Comerci. Oro'],
            ['catalog_id' => '15', 'code' => '6003', 'active' => true, 'description' => 'Comercialización de Oro: N° Resolución que autoriza Planta de Beneficio'],
            ['catalog_id' => '15', 'code' => '6004', 'active' => true, 'description' => 'Comercialización de Oro: Ley Mineral (% concent. oro)'],
            ['catalog_id' => '15', 'code' => '7000', 'active' => true, 'description' => 'Primera venta de mercancia identificable entre usuarios de la zona comercial'],
            ['catalog_id' => '15', 'code' => '7001', 'active' => true, 'description' => 'Venta exonerada del IGV-ISC-IPM. Prohibida la venta fuera de la zona comercial de Tacna'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '16', 'code' => '01', 'active' => true, 'description' => 'Precio unitario (incluye el IGV)'],
            ['catalog_id' => '16', 'code' => '02', 'active' => true, 'description' => 'Valor referencial unitario en operaciones no onerosas'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '17', 'code' => '01', 'active' => true, 'description' => 'Venta lnterna'],
            ['catalog_id' => '17', 'code' => '02', 'active' => true, 'description' => 'Exportación de bienes'],
            ['catalog_id' => '17', 'code' => '03', 'active' => true, 'description' => 'No Domiciliados'],
            ['catalog_id' => '17', 'code' => '04', 'active' => true, 'description' => 'Venta Interna – Anticipos'],
            ['catalog_id' => '17', 'code' => '05', 'active' => true, 'description' => 'Venta Itinerante'],
            ['catalog_id' => '17', 'code' => '06', 'active' => true, 'description' => 'Factura Guía'],
            ['catalog_id' => '17', 'code' => '07', 'active' => true, 'description' => 'Venta Arroz Pilado'],
            ['catalog_id' => '17', 'code' => '08', 'active' => true, 'description' => 'Factura - Comprobante de Percepción'],
            ['catalog_id' => '17', 'code' => '10', 'active' => true, 'description' => 'Factura - Guía remitente'],
            ['catalog_id' => '17', 'code' => '11', 'active' => true, 'description' => 'Factura - Guía transportista'],
            ['catalog_id' => '17', 'code' => '12', 'active' => true, 'description' => 'Boleta de venta – Comprobante de Percepción.'],
            ['catalog_id' => '17', 'code' => '13', 'active' => true, 'description' => 'Gasto Deducible Persona Natural'],
            ['catalog_id' => '17', 'code' => '14', 'active' => true, 'description' => 'Exportación de servicios – prestación de servicios de hospedaje No Dom'],
            ['catalog_id' => '17', 'code' => '15', 'active' => true, 'description' => 'Exportación de servicios – Transporte de navieras'],
            ['catalog_id' => '17', 'code' => '16', 'active' => true, 'description' => 'Exportación de servicios – servicios  a naves y aeronaves de bandera extranjera'],
            ['catalog_id' => '17', 'code' => '17', 'active' => true, 'description' => 'Exportación de servicios – RES'],
            ['catalog_id' => '17', 'code' => '18', 'active' => true, 'description' => 'Exportación de servicios  - Servicios que conformen un Paquete Turístico'],
            ['catalog_id' => '17', 'code' => '19', 'active' => true, 'description' => 'Exportación de servicios – Servicios complementarios al transporte de carga'],
            ['catalog_id' => '17', 'code' => '20', 'active' => true, 'description' => 'Exportación de servicios – Suministro de energía eléctrica a favor de sujetos domiciliados en ZED'],
            ['catalog_id' => '17', 'code' => '21', 'active' => true, 'description' => 'Exportación de servicios – Prestación servicios realizados parcialmente en el extranjero'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '18', 'code' => '01', 'active' => true, 'description' => 'Transporte público'],
            ['catalog_id' => '18', 'code' => '02', 'active' => true, 'description' => 'Transporte privado'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '19', 'code' => '1', 'active' => true, 'description' => 'Adicionar'],
            ['catalog_id' => '19', 'code' => '2', 'active' => true, 'description' => 'Modificar'],
            ['catalog_id' => '19', 'code' => '3', 'active' => true, 'description' => 'Anulado'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '20', 'code' => '01', 'active' => true, 'description' => 'Venta'],
            ['catalog_id' => '20', 'code' => '02', 'active' => true, 'description' => 'Compra'],
            ['catalog_id' => '20', 'code' => '04', 'active' => true, 'description' => 'Traslado entre establecimientos de la misma empresa'],
            ['catalog_id' => '20', 'code' => '08', 'active' => true, 'description' => 'Importación'],
            ['catalog_id' => '20', 'code' => '09', 'active' => true, 'description' => 'Exportación'],
            ['catalog_id' => '20', 'code' => '13', 'active' => true, 'description' => 'Otros'],
            ['catalog_id' => '20', 'code' => '14', 'active' => true, 'description' => 'Venta sujeta a confirmación del comprador'],
            ['catalog_id' => '20', 'code' => '18', 'active' => true, 'description' => 'Traslado emisor itinerante CP'],
            ['catalog_id' => '20', 'code' => '19', 'active' => true, 'description' => 'Traslado a zona primaria'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '21', 'code' => '01', 'active' => true, 'description' => 'Numeración DAM'],
            ['catalog_id' => '21', 'code' => '02', 'active' => true, 'description' => 'Número de orden de entrega'],
            ['catalog_id' => '21', 'code' => '03', 'active' => true, 'description' => 'Número SCOP'],
            ['catalog_id' => '21', 'code' => '04', 'active' => true, 'description' => 'Número de manifiesto de carga'],
            ['catalog_id' => '21', 'code' => '05', 'active' => true, 'description' => 'Número de constancia de detracción'],
            ['catalog_id' => '21', 'code' => '06', 'active' => true, 'description' => 'Otros'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '22', 'code' => '01', 'active' => true, 'percentage' => 2,   'description' => 'Percepción Venta Interna'],
            ['catalog_id' => '22', 'code' => '02', 'active' => true, 'percentage' => 1,   'description' => 'Percepción a la adquisición de combustible'],
            ['catalog_id' => '22', 'code' => '03', 'active' => true, 'percentage' => 0.5, 'description' => 'Percepción realizada al agente de percepción con tasa especial'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '23', 'code' => '01', 'active' => true, 'percentage' => 3, 'description' => 'Tasa 3%'],
            ['catalog_id' => '23', 'code' => '02', 'active' => true, 'percentage' => 6, 'description' => 'Tasa 6%'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '51', 'code' => '0101', 'active' => true,  'exportation' => false, 'description' => 'Venta interna'],
            ['catalog_id' => '51', 'code' => '0112', 'active' => false, 'exportation' => false, 'description' => 'Venta Interna - Sustenta Gastos Deducibles Persona Natural'],
            ['catalog_id' => '51', 'code' => '0113', 'active' => false, 'exportation' => false, 'description' => 'Venta Interna - NRUS'],
            ['catalog_id' => '51', 'code' => '0200', 'active' => true,  'exportation' => true,  'description' => 'Exportación de Bienes'],
            ['catalog_id' => '51', 'code' => '0201', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Prestación servicios realizados íntegramente en el país'],
            ['catalog_id' => '51', 'code' => '0202', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Prestación de servicios de hospedaje No Domiciliado'],
            ['catalog_id' => '51', 'code' => '0203', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Transporte de navieras'],
            ['catalog_id' => '51', 'code' => '0204', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Servicios a naves y aeronaves de bandera extranjera'],
            ['catalog_id' => '51', 'code' => '0205', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios - Servicios que conformen un Paquete Turístico'],
            ['catalog_id' => '51', 'code' => '0206', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Servicios complementarios al transporte de carga'],
            ['catalog_id' => '51', 'code' => '0207', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Suministro de energía eléctrica a favor de sujetos domiciliados en ZED'],
            ['catalog_id' => '51', 'code' => '0208', 'active' => false, 'exportation' => true,  'description' => 'Exportación de Servicios – Prestación servicios realizados parcialmente en el extranjero'],
            ['catalog_id' => '51', 'code' => '0301', 'active' => false, 'exportation' => false, 'description' => 'Operaciones con Carta de porte aéreo (emitidas en el ámbito nacional)'],
            ['catalog_id' => '51', 'code' => '0302', 'active' => false, 'exportation' => false, 'description' => 'Operaciones de Transporte ferroviario de pasajeros'],
            ['catalog_id' => '51', 'code' => '0303', 'active' => false, 'exportation' => false, 'description' => 'Operaciones de Pago de regalía petrolera'],
            ['catalog_id' => '51', 'code' => '0401', 'active' => false, 'exportation' => false, 'description' => 'Ventas no domiciliados que no califican como exportación'],
            ['catalog_id' => '51', 'code' => '1001', 'active' => false, 'exportation' => false, 'description' => 'Operación Sujeta a Detracción'],
            ['catalog_id' => '51', 'code' => '1002', 'active' => false, 'exportation' => false, 'description' => 'Operación Sujeta a Detracción- Recursos Hidrobiológicos'],
            ['catalog_id' => '51', 'code' => '1003', 'active' => false, 'exportation' => false, 'description' => 'Operación Sujeta a Detracción- Servicios de Transporte Pasajeros'],
            ['catalog_id' => '51', 'code' => '1004', 'active' => false, 'exportation' => false, 'description' => 'Operación Sujeta a Detracción- Servicios de Transporte Carga'],
            ['catalog_id' => '51', 'code' => '2001', 'active' => false, 'exportation' => false, 'description' => 'Operación Sujeta a Percepción'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '52', 'code' => '1000', 'active' => true, 'description' => 'Monto en Letras'],
            ['catalog_id' => '52', 'code' => '1002', 'active' => true, 'description' => 'TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE'],
            ['catalog_id' => '52', 'code' => '2000', 'active' => true, 'description' => 'COMPROBANTE DE PERCEPCIÓN'],
            ['catalog_id' => '52', 'code' => '2001', 'active' => true, 'description' => 'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'],
            ['catalog_id' => '52', 'code' => '2002', 'active' => true, 'description' => 'SERVICIOS PRESTADOS EN LA AMAZONÍA  REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'],
            ['catalog_id' => '52', 'code' => '2003', 'active' => true, 'description' => 'CONTRATOS DE CONSTRUCCIÓN EJECUTADOS  EN LA AMAZONÍA REGIÓN SELVA'],
            ['catalog_id' => '52', 'code' => '2004', 'active' => true, 'description' => 'Agencia de Viaje - Paquete turístico'],
            ['catalog_id' => '52', 'code' => '2005', 'active' => true, 'description' => 'Venta realizada por emisor itinerante'],
            ['catalog_id' => '52', 'code' => '2006', 'active' => true, 'description' => 'Operación sujeta a detracción'],
            ['catalog_id' => '52', 'code' => '2007', 'active' => true, 'description' => 'Operación sujeta al IVAP'],
            ['catalog_id' => '52', 'code' => '2008', 'active' => true, 'description' => 'VENTA EXONERADA DEL IGV-ISC-IPM. PROHIBIDA LA VENTA FUERA DE LA ZONA COMERCIAL DE TACNA'],
            ['catalog_id' => '52', 'code' => '2009', 'active' => true, 'description' => 'PRIMERA VENTA DE MERCANCÍA IDENTIFICABLE ENTRE USUARIOS DE LA ZONA COMERCIAL'],
            ['catalog_id' => '52', 'code' => '2010', 'active' => true, 'description' => 'Restitucion Simplificado de Derechos Arancelarios'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '53', 'code'=> '00', 'active' => true,  'base' => true,  'level' => 'item',   'type' => 'discount', 'description' => 'Descuentos que afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '01', 'active' => true,  'base' => false, 'level' => 'item',   'type' => 'discount', 'description' => 'Descuentos que no afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '02', 'active' => true,  'base' => true,  'level' => 'global', 'type' => 'discount', 'description' => 'Descuentos globales que afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '03', 'active' => true,  'base' => false, 'level' => 'global', 'type' => 'discount', 'description' => 'Descuentos globales que no afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '04', 'active' => false, 'base' => true,  'level' => 'global', 'type' => 'discount', 'description' => 'Descuentos globales por anticipos gravados que afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '05', 'active' => false, 'base' => false, 'level' => 'global', 'type' => 'discount', 'description' => 'Descuentos globales por anticipos exonerados'],
            ['catalog_id' => '53', 'code'=> '06', 'active' => false, 'base' => false, 'level' => 'global', 'type' => 'discount', 'description' => 'Descuentos globales por anticipos inafectos'],
            ['catalog_id' => '53', 'code'=> '45', 'active' => false, 'base' => true,  'level' => 'global', 'type' => 'charge',   'description' => 'FISE'],
            ['catalog_id' => '53', 'code'=> '46', 'active' => true,  'base' => false, 'level' => 'global', 'type' => 'charge',   'description' => 'Recargo al consumo y/o propinas'],
            ['catalog_id' => '53', 'code'=> '47', 'active' => true,  'base' => true,  'level' => 'item',   'type' => 'charge',   'description' => 'Cargos que afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '48', 'active' => true,  'base' => false, 'level' => 'item',   'type' => 'charge',   'description' => 'Cargos que no afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '49', 'active' => true,  'base' => true,  'level' => 'global', 'type' => 'charge',   'description' => 'Cargos globales que afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '50', 'active' => true,  'base' => false, 'level' => 'global', 'type' => 'charge',   'description' => 'Cargos globales que no afectan la base imponible del IGV/IVAP'],
            ['catalog_id' => '53', 'code'=> '51', 'active' => false, 'base' => true,  'level' => 'global', 'type' => 'charge',   'description' => 'Percepción venta interna'],
            ['catalog_id' => '53', 'code'=> '52', 'active' => false, 'base' => true,  'level' => 'global', 'type' => 'charge',   'description' => 'Percepción a la adquisición de combustible'],
            ['catalog_id' => '53', 'code'=> '53', 'active' => false, 'base' => true,  'level' => 'global', 'type' => 'charge',   'description' => 'Percepción realizada al agente de percepción con tasa especial'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '54', 'code' => '001', 'active' =>true, 'percentage' => 0, 'description' => 'Azúcar y melaza de caña'],
            ['catalog_id' => '54', 'code' => '002', 'active' =>true, 'percentage' => 0, 'description' => 'Arroz'],
            ['catalog_id' => '54', 'code' => '003', 'active' =>true, 'percentage' => 0, 'description' => 'Alcohol etílico'],
            ['catalog_id' => '54', 'code' => '004', 'active' =>true, 'percentage' => 4, 'description' => 'Recursos hidrobiológicos'],
            ['catalog_id' => '54', 'code' => '005', 'active' =>true, 'percentage' => 4, 'description' => 'Maíz amarillo duro'],
            ['catalog_id' => '54', 'code' => '007', 'active' =>true, 'percentage' => 0, 'description' => 'Caña de azúcar'],
            ['catalog_id' => '54', 'code' => '008', 'active' =>true, 'percentage' => 4, 'description' => 'Madera'],
            ['catalog_id' => '54', 'code' => '009', 'active' =>true, 'percentage' => 0, 'description' => 'Arena y piedra'],
            ['catalog_id' => '54', 'code' => '010', 'active' =>true, 'percentage' => 0, 'description' => 'Residuos, subproductos, desechos, recortes y desperdicios'],
            ['catalog_id' => '54', 'code' => '011', 'active' =>true, 'percentage' => 0, 'description' => 'Bienes gravados con el IGV, o renuncia a la exoneración'],
            ['catalog_id' => '54', 'code' => '012', 'active' =>true, 'percentage' => 0, 'description' => 'Intermediación laboral y tercerización'],
            ['catalog_id' => '54', 'code' => '013', 'active' =>true, 'percentage' => 0, 'description' => 'Animales vivos'],
            ['catalog_id' => '54', 'code' => '014', 'active' =>true, 'percentage' => 4, 'description' => 'Carnes y despojos comestibles'],
            ['catalog_id' => '54', 'code' => '015', 'active' =>true, 'percentage' => 0, 'description' => 'Abonos, cueros y pieles de origen animal'],
            ['catalog_id' => '54', 'code' => '016', 'active' =>true, 'percentage' => 0, 'description' => 'Aceite de pescado'],
            ['catalog_id' => '54', 'code' => '017', 'active' =>true, 'percentage' => 4, 'description' => 'Harina, polvo y “pellets” de pescado, crustáceos, moluscos y demás invertebrados acuáticos'],
            ['catalog_id' => '54', 'code' => '019', 'active' =>true, 'percentage' => 0, 'description' => 'Arrendamiento de bienes muebles'],
            ['catalog_id' => '54', 'code' => '020', 'active' =>true, 'percentage' => 0, 'description' => 'Mantenimiento y reparación de bienes muebles'],
            ['catalog_id' => '54', 'code' => '021', 'active' =>true, 'percentage' => 0, 'description' => 'Movimiento de carga'],
            ['catalog_id' => '54', 'code' => '022', 'active' =>true, 'percentage' => 0, 'description' => 'Otros servicios empresariales'],
            ['catalog_id' => '54', 'code' => '024', 'active' =>true, 'percentage' => 0, 'description' => 'Comisión mercantil'],
            ['catalog_id' => '54', 'code' => '025', 'active' =>true, 'percentage' => 0, 'description' => 'Fabricación de bienes por encargo'],
            ['catalog_id' => '54', 'code' => '026', 'active' =>true, 'percentage' => 0, 'description' => 'Servicio de transporte de personas'],
            ['catalog_id' => '54', 'code' => '027', 'active' =>true, 'percentage' => 4, 'description' => 'Servicio de transporte de carga'],
            ['catalog_id' => '54', 'code' => '028', 'active' =>true, 'percentage' => 0, 'description' => 'Transporte de pasajeros'],
            ['catalog_id' => '54', 'code' => '030', 'active' =>true, 'percentage' => 4, 'description' => 'Contratos de construcción'],
            ['catalog_id' => '54', 'code' => '031', 'active' =>true, 'percentage' => 0, 'description' => 'Oro gravado con el IGV'],
            ['catalog_id' => '54', 'code' => '034', 'active' =>true, 'percentage' => 0, 'description' => 'Minerales metálicos no auríferos'],
            ['catalog_id' => '54', 'code' => '035', 'active' =>true, 'percentage' => 0, 'description' => 'Bienes exonerados del IGV'],
            ['catalog_id' => '54', 'code' => '036', 'active' =>true, 'percentage' => 0, 'description' => 'Oro y demás minerales metálicos exonerados del IGV'],
            ['catalog_id' => '54', 'code' => '037', 'active' =>true, 'percentage' => 0, 'description' => 'Demás servicios gravados con el IGV'],
            ['catalog_id' => '54', 'code' => '039', 'active' =>true, 'percentage' => 0, 'description' => 'Minerales no metálicos'],
            ['catalog_id' => '54', 'code' => '040', 'active' =>true, 'percentage' => 4, 'description' => 'Bien inmueble gravado con IGV'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '55', 'code' => '3001', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Matrícula de la embarcación'],
            ['catalog_id' => '55', 'code' => '3002', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Nombre de la embarcación'],
            ['catalog_id' => '55', 'code' => '3003', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Tipo de especie vendida'],
            ['catalog_id' => '55', 'code' => '3004', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Lugar de descarga'],
            ['catalog_id' => '55', 'code' => '3005', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Fecha de descarga'],
            ['catalog_id' => '55', 'code' => '3006', 'active' => true, 'description' => 'Detracciones: Recursos Hidrobiológicos-Cantidad de especie vendida'],
            ['catalog_id' => '55', 'code' => '3050', 'active' => true, 'description' => 'Transportre Terreste - Número de asiento'],
            ['catalog_id' => '55', 'code' => '3051', 'active' => true, 'description' => 'Transporte Terrestre - Información de manifiesto de pasajeros'],
            ['catalog_id' => '55', 'code' => '3052', 'active' => true, 'description' => 'Transporte Terrestre - Número de documento de identidad del pasajero'],
            ['catalog_id' => '55', 'code' => '3053', 'active' => true, 'description' => 'Transporte Terrestre - Tipo de documento de identidad del pasajero'],
            ['catalog_id' => '55', 'code' => '3054', 'active' => true, 'description' => 'Transporte Terrestre - Nombres y apellidos del pasajero'],
            ['catalog_id' => '55', 'code' => '3055', 'active' => true, 'description' => 'Transporte Terrestre - Ciudad o lugar de destino - Ubigeo'],
            ['catalog_id' => '55', 'code' => '3056', 'active' => true, 'description' => 'Transporte Terrestre - Ciudad o lugar de destino - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '3057', 'active' => true, 'description' => 'Transporte Terrestre - Ciudad o lugar de origen - Ubigeo'],
            ['catalog_id' => '55', 'code' => '3058', 'active' => true, 'description' => 'Transporte Terrestre - Ciudad o lugar de origen - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '3059', 'active' => true, 'description' => 'Transporte Terrestre - Fecha de inicio programado'],
            ['catalog_id' => '55', 'code' => '3060', 'active' => true, 'description' => 'Transporte Terrestre - Hora de inicio programado'],
            ['catalog_id' => '55', 'code' => '4000', 'active' => true, 'description' => 'Beneficio Hospedajes-Paquete turístico: Código de país de emisión del pasaporte'],
            ['catalog_id' => '55', 'code' => '4001', 'active' => true, 'description' => 'Beneficio Hospedajes: Código de país de residencia del sujeto no domiciliado'],
            ['catalog_id' => '55', 'code' => '4002', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de ingreso al país'],
            ['catalog_id' => '55', 'code' => '4003', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de Ingreso al Establecimiento'],
            ['catalog_id' => '55', 'code' => '4004', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de Salida del Establecimiento'],
            ['catalog_id' => '55', 'code' => '4005', 'active' => true, 'description' => 'Beneficio Hospedajes: Número de Días de Permanencia'],
            ['catalog_id' => '55', 'code' => '4006', 'active' => true, 'description' => 'Beneficio Hospedajes: Fecha de Consumo'],
            ['catalog_id' => '55', 'code' => '4007', 'active' => true, 'description' => 'Beneficio Hospedajes-Paquete turístico: Nombres y apellidos del huesped'],
            ['catalog_id' => '55', 'code' => '4008', 'active' => true, 'description' => 'Beneficio Hospedajes-Paquete turístico: Tipo de documento de identidad del huesped'],
            ['catalog_id' => '55', 'code' => '4009', 'active' => true, 'description' => 'Beneficio Hospedajes-Paquete turístico: Número de documento de identidad del huesped'],
            ['catalog_id' => '55', 'code' => '4030', 'active' => true, 'description' => 'Carta Porte Aéreo:  Lugar de origen - Código de ubigeo'],
            ['catalog_id' => '55', 'code' => '4031', 'active' => true, 'description' => 'Carta Porte Aéreo:  Lugar de origen - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '4032', 'active' => true, 'description' => 'Carta Porte Aéreo:  Lugar de destino - Código de ubigeo'],
            ['catalog_id' => '55', 'code' => '4033', 'active' => true, 'description' => 'Carta Porte Aéreo:  Lugar de destino - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '4040', 'active' => true, 'description' => 'BVME transporte ferroviario: Pasajero - Apellidos y Nombres'],
            ['catalog_id' => '55', 'code' => '4041', 'active' => true, 'description' => 'BVME transporte ferroviario: Pasajero - Tipo de documento de identidad'],
            ['catalog_id' => '55', 'code' => '4042', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de origen - Código de ubigeo'],
            ['catalog_id' => '55', 'code' => '4043', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de origen - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '4044', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de destino - Código de ubigeo'],
            ['catalog_id' => '55', 'code' => '4045', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de destino - Dirección detallada'],
            ['catalog_id' => '55', 'code' => '4046', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte:Número de asiento'],
            ['catalog_id' => '55', 'code' => '4047', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Hora programada de inicio de viaje'],
            ['catalog_id' => '55', 'code' => '4048', 'active' => true, 'description' => 'BVME transporte ferroviario: Servicio transporte: Fecha programada de inicio de viaje'],
            ['catalog_id' => '55', 'code' => '4049', 'active' => true, 'description' => 'BVME transporte ferroviario: Pasajero - Número de documento de identidad'],
            ['catalog_id' => '55', 'code' => '4060', 'active' => true, 'description' => 'Regalía Petrolera: Decreto Supremo de aprobación del contrato'],
            ['catalog_id' => '55', 'code' => '4061', 'active' => true, 'description' => 'Regalía Petrolera: Area de contrato (Lote)'],
            ['catalog_id' => '55', 'code' => '4062', 'active' => true, 'description' => 'Regalía Petrolera: Periodo de pago - Fecha de inicio'],
            ['catalog_id' => '55', 'code' => '4063', 'active' => true, 'description' => 'Regalía Petrolera: Periodo de pago - Fecha de fin'],
            ['catalog_id' => '55', 'code' => '4064', 'active' => true, 'description' => 'Regalía Petrolera: Fecha de Pago'],
            ['catalog_id' => '55', 'code' => '5000', 'active' => true, 'description' => 'Proveedores Estado: Número de Expediente'],
            ['catalog_id' => '55', 'code' => '5001', 'active' => true, 'description' => 'Proveedores Estado: Código de Unidad Ejecutora'],
            ['catalog_id' => '55', 'code' => '5002', 'active' => true, 'description' => 'Proveedores Estado: N° de Proceso de Selección'],
            ['catalog_id' => '55', 'code' => '5003', 'active' => true, 'description' => 'Proveedores Estado: N° de Contrato'],
            ['catalog_id' => '55', 'code' => '5010', 'active' => true, 'description' => 'Numero de Placa'],
            ['catalog_id' => '55', 'code' => '5011', 'active' => true, 'description' => 'Categoria'],
            ['catalog_id' => '55', 'code' => '5012', 'active' => true, 'description' => 'Marca'],
            ['catalog_id' => '55', 'code' => '5013', 'active' => true, 'description' => 'Modelo'],
            ['catalog_id' => '55', 'code' => '5014', 'active' => true, 'description' => 'Color'],
            ['catalog_id' => '55', 'code' => '5015', 'active' => true, 'description' => 'Motor'],
            ['catalog_id' => '55', 'code' => '5016', 'active' => true, 'description' => 'Combustible'],
            ['catalog_id' => '55', 'code' => '5017', 'active' => true, 'description' => 'Form. Rodante'],
            ['catalog_id' => '55', 'code' => '5018', 'active' => true, 'description' => 'VIN'],
            ['catalog_id' => '55', 'code' => '5019', 'active' => true, 'description' => 'Serie/Chasis'],
            ['catalog_id' => '55', 'code' => '5020', 'active' => true, 'description' => 'Año fabricacion'],
            ['catalog_id' => '55', 'code' => '5021', 'active' => true, 'description' => 'Año modelo'],
            ['catalog_id' => '55', 'code' => '5022', 'active' => true, 'description' => 'Version'],
            ['catalog_id' => '55', 'code' => '5023', 'active' => true, 'description' => 'Ejes'],
            ['catalog_id' => '55', 'code' => '5024', 'active' => true, 'description' => 'Asientos'],
            ['catalog_id' => '55', 'code' => '5025', 'active' => true, 'description' => 'Pasajeros'],
            ['catalog_id' => '55', 'code' => '5026', 'active' => true, 'description' => 'Ruedas'],
            ['catalog_id' => '55', 'code' => '5027', 'active' => true, 'description' => 'Carroceria'],
            ['catalog_id' => '55', 'code' => '5028', 'active' => true, 'description' => 'Potencia'],
            ['catalog_id' => '55', 'code' => '5029', 'active' => true, 'description' => 'Cilindros'],
            ['catalog_id' => '55', 'code' => '5030', 'active' => true, 'description' => 'Ciliindrada'],
            ['catalog_id' => '55', 'code' => '5031', 'active' => true, 'description' => 'Peso Bruto'],
            ['catalog_id' => '55', 'code' => '5032', 'active' => true, 'description' => 'Peso Neto'],
            ['catalog_id' => '55', 'code' => '5033', 'active' => true, 'description' => 'Carga Util'],
            ['catalog_id' => '55', 'code' => '5034', 'active' => true, 'description' => 'Longitud'],
            ['catalog_id' => '55', 'code' => '5035', 'active' => true, 'description' => 'Altura'],
            ['catalog_id' => '55', 'code' => '5036', 'active' => true, 'description' => 'Ancho'],
            ['catalog_id' => '55', 'code' => '6000', 'active' => true, 'description' => 'Comercialización de Oro:  Código Unico Concesión Minera'],
            ['catalog_id' => '55', 'code' => '6001', 'active' => true, 'description' => 'Comercialización de Oro:  N° declaración compromiso'],
            ['catalog_id' => '55', 'code' => '6002', 'active' => true, 'description' => 'Comercialización de Oro:  N° Reg. Especial .Comerci. Oro'],
            ['catalog_id' => '55', 'code' => '6003', 'active' => true, 'description' => 'Comercialización de Oro:  N° Resolución que autoriza Planta de Beneficio'],
            ['catalog_id' => '55', 'code' => '6004', 'active' => true, 'description' => 'Comercialización de Oro: Ley Mineral (% concent. oro)'],
            ['catalog_id' => '55', 'code' => '7000', 'active' => true, 'description' => 'Gastos Art. 37 Renta:  Número de Placa'],
            ['catalog_id' => '55', 'code' => '7001', 'active' => true, 'description' => 'Créditos Hipotecarios: Tipo de préstamo'],
            ['catalog_id' => '55', 'code' => '7002', 'active' => true, 'description' => 'Créditos Hipotecarios: Indicador de Primera Vivienda'],
            ['catalog_id' => '55', 'code' => '7003', 'active' => true, 'description' => 'Créditos Hipotecarios: Partida Registral'],
            ['catalog_id' => '55', 'code' => '7004', 'active' => true, 'description' => 'Créditos Hipotecarios: Número de contrato'],
            ['catalog_id' => '55', 'code' => '7005', 'active' => true, 'description' => 'Créditos Hipotecarios: Fecha de otorgamiento del crédito'],
            ['catalog_id' => '55', 'code' => '7006', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Código de ubigeo'],
            ['catalog_id' => '55', 'code' => '7007', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Dirección completa'],
            ['catalog_id' => '55', 'code' => '7008', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Urbanización'],
            ['catalog_id' => '55', 'code' => '7009', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Provincia'],
            ['catalog_id' => '55', 'code' => '7010', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Distrito'],
            ['catalog_id' => '55', 'code' => '7011', 'active' => true, 'description' => 'Créditos Hipotecarios: Dirección del predio - Departamento'],
            ['catalog_id' => '55', 'code' => '7020', 'active' => true, 'description' => 'Partida Arancelaria'],
        ]);

        DB::table('codes')->insert([
            ['catalog_id' => '59', 'code' => '001', 'active' => true, 'description' => 'Depósito en cuenta'],
            ['catalog_id' => '59', 'code' => '002', 'active' => true, 'description' => 'Giro'],
            ['catalog_id' => '59', 'code' => '003', 'active' => true, 'description' => 'Transferencia de fondos'],
            ['catalog_id' => '59', 'code' => '004', 'active' => true, 'description' => 'Orden de pago'],
            ['catalog_id' => '59', 'code' => '005', 'active' => true, 'description' => 'Tarjeta de débito'],
            ['catalog_id' => '59', 'code' => '006', 'active' => true, 'description' => 'Tarjeta de crédito emitida en el país por una empresa del sistema financiero'],
            ['catalog_id' => '59', 'code' => '007', 'active' => true, 'description' => 'Cheques con la cláusula de "NO NEGOCIABLE", "INTRANSFERIBLES", "NO A LA ORDEN" u otra equivalente, a que se refiere el inciso g) del artículo 5° de la ley'],
            ['catalog_id' => '59', 'code' => '008', 'active' => true, 'description' => 'Efectivo, por operaciones en las que no existe obligación de utilizar medio de pago'],
            ['catalog_id' => '59', 'code' => '009', 'active' => true, 'description' => 'Efectivo, en los demás casos'],
            ['catalog_id' => '59', 'code' => '010', 'active' => true, 'description' => 'Medios de pago usados en comercio exterior'],
            ['catalog_id' => '59', 'code' => '011', 'active' => true, 'description' => 'Documentos emitidos por las EDPYMES y las cooperativas de ahorro y crédito no autorizadas a captar depósitos del público'],
            ['catalog_id' => '59', 'code' => '012', 'active' => true, 'description' => 'Tarjeta de crédito emitida en el país o en el exterior por una empresa no perteneciente al sistema financiero, cuyo objeto principal sea la emisión y administración de tarjetas de crédito'],
            ['catalog_id' => '59', 'code' => '013', 'active' => true, 'description' => 'Tarjetas de crédito emitidas en el exterior por empresas bancarias o financieras no domiciliadas'],
            ['catalog_id' => '59', 'code' => '101', 'active' => true, 'description' => 'Transferencias – Comercio exterior'],
            ['catalog_id' => '59', 'code' => '102', 'active' => true, 'description' => 'Cheques bancarios - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '103', 'active' => true, 'description' => 'Orden de pago simple - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '104', 'active' => true, 'description' => 'Orden de pago documentario - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '105', 'active' => true, 'description' => 'Remesa simple - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '106', 'active' => true, 'description' => 'Remesa documentaria - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '107', 'active' => true, 'description' => 'Carta de crédito simple - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '108', 'active' => true, 'description' => 'Carta de crédito documentario - Comercio exterior'],
            ['catalog_id' => '59', 'code' => '999', 'active' => true, 'description' => 'Otros medios de pago']
        ]);

        Schema::create('document_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
            $table->string('short')->nullable();
            $table->boolean('active');
        });

        DB::table('document_types')->insert([
            ['id' => '01', 'description' => 'FACTURA ELECTRÓNICA',                                          'short' => 'FT', 'active' => true],
            ['id' => '03', 'description' => 'BOLETA DE VENTA ELECTRÓNICA',                                  'short' => 'BV', 'active' => true],
            ['id' => '06', 'description' => 'Carta de porte aéreo',                                         'short' => null, 'active' => false],
            ['id' => '07', 'description' => 'NOTA DE CRÉDITO',                                              'short' => 'NC', 'active' => true],
            ['id' => '08', 'description' => 'NOTA DE DÉBITO',                                               'short' => 'ND', 'active' => true],
            ['id' => '09', 'description' => 'Guia de remisión remitente',                                   'short' => null, 'active' => false],
            ['id' => '12', 'description' => 'Ticket de maquina registradora',                               'short' => null, 'active' => false],
            ['id' => '13', 'description' => 'Documento emitido por bancos, instituciones financieras, 
                                             crediticias y de seguros que se encuentren bajo el control 
                                             de la superintendencia de banca y seguros',                    'short' => null, 'active' => false],
            ['id' => '14', 'description' => 'Recibo de servicios públicos',                                 'short' => null, 'active' => false],
            ['id' => '15', 'description' => 'Boletos emitidos por el servicio de transporte terrestre
                                             regular urbano de pasajeros y el ferroviario público de
                                             pasajeros prestado en vía férrea local.',                      'short' => null, 'active' => false],
            ['id' => '16', 'description' => 'Boleto de viaje emitido por las empresas de transporte
                                             público interprovincial de pasajeros',                         'short' => null, 'active' => false],
            ['id' => '18', 'description' => 'Documentos emitidos por las afp',                              'short' => null, 'active' => false],
            ['id' => '20', 'description' => 'COMPROBANTE DE RETENCIÓN ELECTRÓNICA',                         'short' => null, 'active' => true],
            ['id' => '21', 'description' => 'Conocimiento de embarque por el servicio de transporte de
                                             carga marítima',                                               'short' => null, 'active' => false],
            ['id' => '24', 'description' => 'Certificado de pago de regalías emitidas por perupetro s.a.',  'short' => null, 'active' => false],
            ['id' => '31', 'description' => 'Guía de remisión transportista',                               'short' => null, 'active' => false],
            ['id' => '37', 'description' => 'Documentos que emitan los concesionarios del servicio de
                                             revisiones técnicas',                                          'short' => null, 'active' => true],
            ['id' => '40', 'description' => 'COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA',                        'short' => null, 'active' => false],
            ['id' => '41', 'description' => 'Comprobante de percepción – venta interna
                                             (físico - formato impreso)',                                   'short' => null, 'active' => false],
            ['id' => '43', 'description' => 'Boleto de compañias de aviación transporte aéreo no regular',  'short' => null, 'active' => false],
            ['id' => '45', 'description' => 'Documentos emitidos por centros educativos y culturales, 
                                             universidades, asociaciones y fundaciones.',                   'short' => null, 'active' => false],
            ['id' => '56', 'description' => 'Comprobante de pago SEAE',                                     'short' => null, 'active' => false],
            ['id' => '71', 'description' => 'Guia de remisión remitente complementaria',                    'short' => null, 'active' => false],
            ['id' => '72', 'description' => 'Guia de remisión transportista complementaria',                'short' => null, 'active' => false],
        ]);

        //02
        Schema::create('currency_types', function (Blueprint $table) {
            $table->char('id', 3)->index();
            $table->string('description');
            $table->string('symbol');
            $table->boolean('active');
        });

        DB::table('currency_types')->insert([
            ['id' => 'PEN', 'description' => 'Soles',               'symbol' => 'S/', 'active' => true],
            ['id' => 'USD', 'description' => 'Dólares Americanos',  'symbol' => '$',  'active' => true],
//            ['id' => 'EUR', 'description' => 'Euros',               'symbol' => '€',  'active' => false],
        ]);

        //03
        Schema::create('unit_types', function (Blueprint $table) {
            $table->string('id', 3)->index();
            $table->string('description');
            $table->string('symbol')->nullable();
            $table->boolean('active');
        });

        DB::table('unit_types')->insert([
            ['id' => 'ZZ',  'description' => 'Servicio',    'symbol' => null, 'active' => true],
            ['id' => 'BX',  'description' => 'Caja',        'symbol' => null, 'active' => true],
            ['id' => 'GLL', 'description' => 'Galones',     'symbol' => null, 'active' => true],
            ['id' => 'GRM', 'description' => 'Gramos',      'symbol' => null, 'active' => true],
            ['id' => 'KGM', 'description' => 'Kilos',       'symbol' => null, 'active' => true],
            ['id' => 'LTR', 'description' => 'Litros',      'symbol' => null, 'active' => true],
            ['id' => 'MTR', 'description' => 'Metros',      'symbol' => null, 'active' => true],
            ['id' => 'FOT', 'description' => 'Pies',        'symbol' => null, 'active' => true],
            ['id' => 'INH', 'description' => 'Pulgadas',    'symbol' => null, 'active' => true],
            ['id' => 'NIU', 'description' => 'Unidades',    'symbol' => null, 'active' => true],
            ['id' => 'YRD', 'description' => 'Yardas',      'symbol' => null, 'active' => true],
            ['id' => 'HUR', 'description' => 'Hora',        'symbol' => null, 'active' => true],
        ]);

        //06
        Schema::create('identity_document_types', function (Blueprint $table) {
            $table->char('id', 1)->index();
            $table->string('description');
            $table->boolean('active');
        });

        DB::table('identity_document_types')->insert([
            ['id' => '0', 'description' => 'Doc.trib.no.dom.sin.ruc',                           'active' =>true],
            ['id' => '1', 'description' => 'DNI',                                               'active' => true],
            ['id' => '4', 'description' => 'CE',                                                'active' => true],
            ['id' => '6', 'description' => 'RUC',                                               'active' => true],
            ['id' => '7', 'description' => 'Pasaporte',                                         'active' => true],
            ['id' => 'A', 'description' => 'Ced. Diplomática de identidad',                     'active' => false],
            ['id' => 'B', 'description' => 'Documento identidad país residencia-no.d',          'active' => false],
            ['id' => 'C', 'description' => 'Tax Identification Number - TIN – Doc Trib PP.NN',  'active' => false],
            ['id' => 'D', 'description' => 'Identification Number - IN – Doc Trib PP. JJ',      'active' => false],
            ['id' => 'E', 'description' => 'TAM- Tarjeta Andina de Migración',                  'active' => false],
        ]);

        //07
        Schema::create('affectation_igv_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
            $table->boolean('exportation');
            $table->boolean('free');
            $table->boolean('active');
        });

        DB::table('affectation_igv_types')->insert([
            ['id' => '10', 'description' => 'Gravado - Operación Onerosa',                  'exportation' => false, 'free' => false, 'active' => true],
            ['id' => '11', 'description' => 'Gravado – Retiro por premio',                  'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '12', 'description' => 'Gravado – Retiro por donación',                'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '13', 'description' => 'Gravado – Retiro',                             'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '14', 'description' => 'Gravado – Retiro por publicidad',              'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '15', 'description' => 'Gravado – Bonificaciones',                     'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '16', 'description' => 'Gravado – Retiro por entrega a trabajadores',  'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '17', 'description' => 'Gravado – IVAP',                               'exportation' => false, 'free' => true,  'active' => false],
            ['id' => '20', 'description' => 'Exonerado - Operación Onerosa',                'exportation' => false, 'free' => false, 'active' => true],
            ['id' => '21', 'description' => 'Exonerado – Transferencia Gratuita',           'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '30', 'description' => 'Inafecto - Operación Onerosa',                 'exportation' => false, 'free' => false, 'active' => true],
            ['id' => '31', 'description' => 'Inafecto – Retiro por Bonificación',           'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '32', 'description' => 'Inafecto – Retiro',                            'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '33', 'description' => 'Inafecto – Retiro por Muestras Médicas',       'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '34', 'description' => 'Inafecto - Retiro por Convenio Colectivo',     'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '35', 'description' => 'Inafecto – Retiro por premio',                 'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '36', 'description' => 'Inafecto - Retiro por publicidad',             'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '37', 'description' => 'Inafecto - Transferencia gratuita',            'exportation' => false, 'free' => true,  'active' => true],
            ['id' => '40', 'description' => 'Exportación de bienes o servicios',            'exportation' => true,  'free' => false, 'active' => true],
        ]);

        //08
        Schema::create('system_isc_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
            $table->boolean('active');
        });

        DB::table('system_isc_types')->insert([
            ['id' => '01', 'description' => 'Sistema al valor',                         'active' =>true],
            ['id' => '02', 'description' => 'Aplicación del Monto Fijo',                'active' =>true],
            ['id' => '03', 'description' => 'Sistema de Precios de Venta al Público',   'active' =>true],
        ]);

        //09
        Schema::create('note_credit_types', function (Blueprint $table) {
            $table->string('id', 2)->index();
            $table->string('description');
            $table->boolean('active');
        });

        DB::table('note_credit_types')->insert([
            ['id' => '01', 'description' => 'Anulación de la operación',              'active' =>true],
            ['id' => '02', 'description' => 'Anulación por error en el RUC',          'active' =>true],
            ['id' => '03', 'description' => 'Corrección por error en la descripción', 'active' =>true],
            ['id' => '04', 'description' => 'Descuento global',                       'active' =>true],
            ['id' => '05', 'description' => 'Descuento por ítem',                     'active' =>true],
            ['id' => '06', 'description' => 'Devolución total',                       'active' =>true],
            ['id' => '07', 'description' => 'Devolución por ítem',                    'active' =>true],
            ['id' => '08', 'description' => 'Bonificación',                           'active' =>true],
            ['id' => '09', 'description' => 'Disminución en el valor',                'active' =>true],
            ['id' => '10', 'description' => 'Otros Conceptos',                        'active' =>true],
            ['id' => '11', 'description' => 'Ajustes de operaciones de exportación',  'active' =>true],
            ['id' => '12', 'description' => 'Ajustes afectos al IVAP',                'active' =>true],
        ]);

        //10
        Schema::create('note_debit_types', function (Blueprint $table) {
            $table->string('id', 2)->index();
            $table->string('description');
            $table->boolean('active');
        });

        DB::table('note_debit_types')->insert([
            ['id' => '01', 'description' => 'Intereses por mora',                    'active' =>true],
            ['id' => '02', 'description' => 'Aumento en el valor',                   'active' =>true],
            ['id' => '03', 'description' => 'Penalidades/ otros conceptos',          'active' =>true],
            ['id' => '10', 'description' => 'Ajustes de operaciones de exportación', 'active' =>true],
            ['id' => '11', 'description' => 'Ajustes afectos al IVAP',               'active' =>true],
        ]);

        //16
        Schema::create('price_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
            $table->boolean('active');
        });

        DB::table('price_types')->insert([
            ['id' => '01', 'description' => 'Precio unitario (incluye el IGV)',                      'active' =>true],
            ['id' => '02', 'description' => 'Valor referencial unitario en operaciones no onerosas', 'active' =>true],
        ]);

        //19
        Schema::create('process_types', function (Blueprint $table) {
            $table->char('id', 1)->index();
            $table->string('description');
        });

        DB::table('process_types')->insert([
            ['id' => '1', 'description' => 'Adicionar'],
            ['id' => '2', 'description' => 'Modificar'],
            ['id' => '3', 'description' => 'Anulado'],
        ]);

//        Código de documentos relacionados (sólo guía de remisión electrónica)
//
//
//        01	Numeración DAM
//        02	Número de orden de entrega
//        03	Número SCOP
//        04	Número de manifiesto de carga
//        05	Número de constancia de detracción
//        06	Otros


        //22
        Schema::create('perception_types', function (Blueprint $table) {
            $table->string('id', 2)->index();
            $table->string('description');
            $table->decimal('percentage', 12, 2);
            $table->boolean('active');
        });

        DB::table('perception_types')->insert([
            ['id' => '01', 'description' => 'Percepción Venta Interna',                                       'percentage' => 2,   'active' =>true],
            ['id' => '02', 'description' => 'Percepción a la adquisición de combustible',                     'percentage' => 1,   'active' =>true],
            ['id' => '03', 'description' => 'Percepción realizada al agente de percepción con tasa especial', 'percentage' => 0.5, 'active' =>true],
        ]);

        //23
        Schema::create('retention_types', function (Blueprint $table) {
            $table->string('id', 2)->index();
            $table->string('description');
            $table->decimal('percentage', 12, 2);
            $table->boolean('active');
        });

        DB::table('retention_types')->insert([
            ['id' => '01', 'description' => 'Tasa 3%', 'percentage' => 3,   'active' =>true],
            ['id' => '02', 'description' => 'Tasa 6%', 'percentage' => 6,   'active' =>true],
        ]);

        //51
        Schema::create('operation_types', function (Blueprint $table) {
            $table->string('id', 4)->index();
            $table->string('description');
            $table->boolean('exportation');
            $table->boolean('active');
        });

        DB::table('operation_types')->insert([
            ['id' => '0101', 'description' => 'Venta interna',                                                      'exportation' => false, 'active' => true],
//            ['id' => '0102', 'description' => 'Venta Interna – Anticipos',                                      'exportation' => false, 'active' => false],
//            ['id' => '0103', 'description' => 'Venta interna - Itinerante',                                     'exportation' => false, 'active' => false],
//            ['id' => '0110', 'description' => 'Venta Interna - Sustenta Traslado de Mercadería - Remitente ',   'exportation' => false, 'active' => false],
//            ['id' => '0111', 'description' => 'Venta Interna - Sustenta Traslado de Mercadería-Transportista',  'exportation' => false, 'active' => false],
            ['id' => '0112', 'description' => 'Venta Interna - Sustenta Gastos Deducibles Persona Natural',         'exportation' => false, 'active' => false],
            ['id' => '0113', 'description' => 'Venta Interna - NRUS',                                               'exportation' => false, 'active' => false],
//            ['id' => '0120', 'description' => 'Venta Interna - Sujeta al IVAP',                                 'exportation' => false, 'active' => false],
//            ['id' => '0121', 'description' => 'Venta Interna - Sujeta al FISE',                                 'exportation' => false, 'active' => false],
//            ['id' => '0122', 'description' => 'Venta Interna - Sujeta a otros impuestos',                       'exportation' => false, 'active' => false],
//            ['id' => '0130', 'description' => 'Venta Interna - Realizadas al Estado',                           'exportation' => false, 'active' => false],
            ['id' => '0200', 'description' => 'Exportación de Bienes',                                              'exportation' => true,  'active' => true],
            ['id' => '0201', 'description' => 'Exportación de Servicios – Prestación servicios
                                               realizados íntegramente en el país',                                 'exportation' => true,  'active' => false],
            ['id' => '0202', 'description' => 'Exportación de Servicios – Prestación de
                                               servicios de hospedaje No Domiciliado',                              'exportation' => true,  'active' => false],
            ['id' => '0203', 'description' => 'Exportación de Servicios – Transporte de navieras',                  'exportation' => true,  'active' => false],
            ['id' => '0204', 'description' => 'Exportación de Servicios – Servicios a naves
                                              y aeronaves de bandera extranjera',                                   'exportation' => true,  'active' => false],
            ['id' => '0205', 'description' => 'Exportación de Servicios - Servicios que
                                               conformen un Paquete Turístico',                                     'exportation' => true,  'active' => false],
            ['id' => '0206', 'description' => 'Exportación de Servicios – Servicios
                                               complementarios al transporte de carga',                             'exportation' => true,  'active' => false],
            ['id' => '0207', 'description' => 'Exportación de Servicios – Suministro
                                               de energía eléctrica a favor de sujetos domiciliados en ZED',        'exportation' => true,  'active' => false],
            ['id' => '0208', 'description' => 'Exportación de Servicios – Prestación
                                               servicios realizados parcialmente en el extranjero',                 'exportation' => true,  'active' => false],
            ['id' => '0301', 'description' => 'Operaciones con Carta de porte aéreo
                                               (emitidas en el ámbito nacional)',                                   'exportation' => false, 'active' => false],
            ['id' => '0302', 'description' => 'Operaciones de Transporte ferroviario de pasajeros',                 'exportation' => false, 'active' => false],
            ['id' => '0303', 'description' => 'Operaciones de Pago de regalía petrolera',                           'exportation' => false, 'active' => false],
            ['id' => '0401', 'description' => 'Ventas no domiciliados que no califican como exportación',           'exportation' => false, 'active' => false],
            ['id' => '1001', 'description' => 'Operación Sujeta a Detracción',                                      'exportation' => false, 'active' => false],
            ['id' => '1002', 'description' => 'Operación Sujeta a Detracción- Recursos Hidrobiológicos',            'exportation' => false, 'active' => false],
            ['id' => '1003', 'description' => 'Operación Sujeta a Detracción- Servicios de Transporte Pasajeros',   'exportation' => false, 'active' => false],
            ['id' => '1004', 'description' => 'Operación Sujeta a Detracción- Servicios de Transporte Carga',       'exportation' => false, 'active' => false],
            ['id' => '2001', 'description' => 'Operación Sujeta a Percepción',                                      'exportation' => false, 'active' => false],
        ]);

        Schema::create('charge_discount_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
            $table->boolean('base');
            $table->enum('type', ['discount', 'charge']);
            $table->enum('level', ['item', 'global']);
            $table->boolean('active');
        });

        DB::table('charge_discount_types')->insert([
            ['id' => '00', 'description' => 'Descuentos que afectan la base imponible del IGV - Item',               'base' => true,  'level' => 'item',   'type' => 'discount', 'active' =>true],
            ['id' => '01', 'description' => 'Descuentos que no afectan la base imponible del IGV - Item',            'base' => false, 'level' => 'item',   'type' => 'discount', 'active' =>true],
            ['id' => '02', 'description' => 'Descuentos globales que afectan la base imponible del IGV - Global',    'base' => true,  'level' => 'global', 'type' => 'discount', 'active' =>true],
            ['id' => '03', 'description' => 'Descuentos globales que no afectan la base imponible del IGV - Global', 'base' => false, 'level' => 'global', 'type' => 'discount', 'active' =>true],
            ['id' => '45', 'description' => 'FISE - Global',                                                         'base' => true,  'level' => 'global', 'type' => 'charge',   'active' =>false],
            ['id' => '46', 'description' => 'Recargo al consumo y/o propinas - Global',                              'base' => false, 'level' => 'global', 'type' => 'charge',   'active' =>true],
            ['id' => '47', 'description' => 'Cargos que afectan la base imponible del IGV - Item',                   'base' => true,  'level' => 'item',   'type' => 'charge',   'active' =>true],
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retention_types');
        Schema::dropIfExists('perception_types');
        Schema::dropIfExists('charge_discount_types');
        Schema::dropIfExists('operation_types');
        Schema::dropIfExists('unit_types');
        Schema::dropIfExists('identity_document_types');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('system_isc_types');
        Schema::dropIfExists('affectation_types');
        Schema::dropIfExists('currency_types');
    }
}
