<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->char('note_type_id', 8);
            $table->string('description');
            $table->unsignedInteger('affected_document_id');
            $table->decimal('total_global_discount', 12, 2)->default(0);
            $table->decimal('total_prepayment', 12, 2)->default(0);

            $table->json('perception')->nullable();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('note_type_id')->references('id')->on('codes');
            $table->foreign('affected_document_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
