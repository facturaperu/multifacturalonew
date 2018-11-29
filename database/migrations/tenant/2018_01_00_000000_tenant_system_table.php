<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
        });

        DB::table('state_types')->insert([
            ['id' => '01', 'description' => 'Registrado'],
            ['id' => '03', 'description' => 'Enviado'],
            ['id' => '05', 'description' => 'Aceptado'],
            ['id' => '07', 'description' => 'Observado'],
            ['id' => '09', 'description' => 'Rechazado'],
            ['id' => '11', 'description' => 'Anulado'],
            ['id' => '13', 'description' => 'Por anular'],
        ]);

        Schema::create('process_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
        });

        DB::table('process_types')->insert([
            ['id' => 1, 'description' => 'Registrar'],
            ['id' => 3, 'description' => 'Anular'],
        ]);

        Schema::create('soap_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
        });

        DB::table('soap_types')->insert([
            ['id' => '01', 'description' => 'Demo'],
            ['id' => '02', 'description' => 'Producción'],
        ]);

        Schema::create('groups', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
        });

        DB::table('groups')->insert([
            ['id' => '01', 'description' => 'Facturas'],
            ['id' => '02', 'description' => 'Boletas'],
        ]);

        Schema::create('charge_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['charge', 'discount']);
            $table->char('charge_discount_type_id', 8);
            $table->enum('level', ['global', 'item', 'both']);
            $table->boolean('base')->default(true);
            $table->string('description');
            $table->decimal('percentage', 10, 2);

            $table->foreign('charge_discount_type_id')->references('id')->on('codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charge_discounts');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('soap_types');
        Schema::dropIfExists('process_types');
        Schema::dropIfExists('state_types');
    }
}
