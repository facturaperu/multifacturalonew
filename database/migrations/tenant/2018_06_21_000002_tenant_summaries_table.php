<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_type_id');
            $table->char('state_type_id', 2);
            $table->char('soap_type_id', 2);
            $table->string('ubl_version');
            $table->date('date_of_issue');
            $table->date('date_of_reference');
            $table->string('identifier');
            $table->string('filename');
            $table->boolean('has_ticket')->default(false);
            $table->string('ticket')->nullable();
            $table->boolean('has_cdr')->default(false);
            $table->timestamps();

            $table->foreign('process_type_id')->references('id')->on('process_types');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summaries');
    }
}
