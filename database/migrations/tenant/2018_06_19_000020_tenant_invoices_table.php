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
            $table->char('operation_type_id', 8);
            $table->date('date_of_due')->nullable();
            $table->decimal('total_free', 12, 2)->default(0);
            $table->decimal('total_global_discount', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2);

            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->json('perception')->nullable();
            $table->json('detraction')->nullable();
            $table->json('prepayments')->nullable();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('operation_type_id')->references('id')->on('codes');
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
