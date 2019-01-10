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
