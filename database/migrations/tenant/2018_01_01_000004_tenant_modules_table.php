<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('modules')->insert([
            ['value' => 'documents', 'description' => 'Ventas'],
            ['value' => 'items', 'description' => 'Productos'],
            ['value' => 'customers', 'description' => 'Clientes'],
            ['value' => 'suppliers', 'description' => 'Proveedores'],
            ['value' => 'purchases', 'description' => 'Compras'],
            ['value' => 'suppliers', 'description' => 'Resúmenes'],
            ['value' => 'suppliers', 'description' => 'Anulaciones'],
            ['value' => 'companies', 'description' => 'Empresa'],
            ['value' => 'retentions', 'description' => 'Retenciones'],
            ['value' => 'dispatches', 'description' => 'Guías de remisión'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}