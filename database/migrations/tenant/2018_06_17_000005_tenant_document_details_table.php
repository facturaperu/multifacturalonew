<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('document_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('item_id');
            $table->text('item_description');
            $table->json('item');
            $table->integer('quantity');
            $table->decimal('unit_value', 12, 2);

            $table->string('affectation_igv_type_code');
            $table->decimal('total_base_igv', 12, 2);
            $table->decimal('percentage_igv', 12, 2);
            $table->decimal('total_igv', 12, 2);

            $table->string('system_isc_type_code')->nullable();
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('percentage_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);

            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('percentage_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2);

            $table->string('price_type_code');
            $table->decimal('unit_price', 12, 2);

            $table->decimal('total_value', 12, 2);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->json('attributes')->nullable();
            $table->json('discounts')->nullable();
            $table->json('charges')->nullable();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
//            $table->foreign('affectation_igv_type_id')->references('id')->on('codes');
//            $table->foreign('system_isc_type_id')->references('id')->on('codes');
//            $table->foreign('price_type_id')->references('id')->on('codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_details');
    }
}
