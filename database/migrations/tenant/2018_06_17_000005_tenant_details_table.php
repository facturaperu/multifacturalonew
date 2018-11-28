<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('item_id');
            $table->string('item_description');
            $table->string('item_code')->nullable();
            $table->string('unit_type_code');
            $table->string('carriage_plate')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_value', 12, 2);
            $table->string('price_type_code');
            $table->decimal('unit_price', 12, 2);
            $table->string('affectation_igv_type_code');
            $table->decimal('total_igv', 12, 2);
            $table->decimal('percentage_igv', 12, 2);
            $table->string('system_isc_type_code')->nullable();
            $table->decimal('total_isc', 12, 2);
            $table->string('charge_type_code')->nullable();
            $table->decimal('charge_percentage', 12, 2);
            $table->decimal('total_charge', 12, 2);
            $table->string('discount_type_code')->nullable();
            $table->decimal('discount_percentage', 12, 2);
            $table->decimal('total_discount', 12, 2);
            $table->decimal('total_value', 12, 2);
            $table->decimal('total', 12, 2);
            $table->json('additional')->nullable();
            $table->string('first_housing_contract_number')->nullable();
            $table->date('first_housing_credit_date')->nullable();

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
