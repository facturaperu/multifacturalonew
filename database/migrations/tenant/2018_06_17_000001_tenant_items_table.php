<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->char('id', 2)->index();
            $table->string('description');
        });

        DB::table('item_types')->insert([
            ['id' => '01', 'description' => 'Producto'],
            ['id' => '02', 'description' => 'Servicio']
        ]);

        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->char('item_type_id', 2);
            $table->string('internal_id')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_code_gs1')->nullable();

            $table->char('unit_type_id', 8);
            $table->char('currency_type_id', 8);
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('unit_type_id')->references('id')->on('codes');
            $table->foreign('currency_type_id')->references('id')->on('codes');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_types');
    }
}