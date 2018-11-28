<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('establishment_id');
            $table->uuid('external_id');
            $table->char('soap_type_id', 2);
            $table->char('state_type_id', 2);
            $table->string('ubl_version');
            $table->char('group_id', 2);
            $table->char('document_type_id', 8);
            $table->unsignedInteger('series_id');
            $table->integer('number');
            $table->date('date_of_issue');
            $table->time('time_of_issue');
            $table->unsignedInteger('customer_id');
            $table->char('currency_type_id', 8);
            $table->decimal('total_other_charges', 12, 2)->default(0);
            $table->decimal('total_exportation', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2);
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);
            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('purchase_order')->nullable();

            $table->json('legends')->nullable();
            $table->json('guides')->nullable();
            $table->json('additional_documents')->nullable();
            $table->json('optional')->nullable();

            $table->string('filename')->nullable();
            $table->string('hash')->nullable();
            $table->text('qr')->nullable();
            $table->boolean('has_xml')->default(false);
            $table->boolean('has_pdf')->default(false);
            $table->boolean('has_cdr')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('document_type_id')->references('id')->on('codes');
            $table->foreign('series_id')->references('id')->on('series');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('currency_type_id')->references('id')->on('codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
