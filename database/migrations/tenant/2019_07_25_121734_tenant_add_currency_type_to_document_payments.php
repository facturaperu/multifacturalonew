<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCurrencyTypeToDocumentPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_payments', function (Blueprint $table) {
            $table->string('currency_type_id')->default('PEN')->after('document_id');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_payments', function (Blueprint $table) {
            $table->dropForeign(['currency_type_id']);
            $table->dropColumn('currency_type_id');
            //
        });
    }
}
