<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantRetentionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retention_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('retention_id');
            $table->char('document_type_id', 2);
            $table->string('number');
            $table->date('date_of_issue');
            $table->date('date_of_retention');
            $table->char('currency_type_id', 3);
            $table->decimal('total_document', 10, 2);
            $table->decimal('total_retention', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('exchange', 10, 2);
            $table->json('payments');

            $table->foreign('retention_id')->references('id')->on('retentions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retention_details');
    }
}
