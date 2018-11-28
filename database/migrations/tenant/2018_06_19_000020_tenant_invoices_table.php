<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->string('operation_type_code');
            $table->date('date_of_due')->nullable();
            $table->decimal('base_global_discount', 12, 2);
            $table->decimal('percentage_global_discount', 12, 2);
            $table->decimal('total_global_discount', 12, 2);
            $table->decimal('total_free', 12, 2);
            $table->decimal('total_prepayment', 12, 2);
            $table->string('purchase_order')->nullable();
            $table->json('detraction')->nullable();
            $table->json('perception')->nullable();
            $table->json('prepayments')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
