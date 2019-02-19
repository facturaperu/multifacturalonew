<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeColumnsToExchangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->renameColumn('buy', 'original');
            $table->renameColumn('sell', 'sale');
            $table->dropColumn('date_original');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->renameColumn('original', 'buy');
            $table->renameColumn('sale', 'sell');
            $table->date('date_original');
        });
    }
}
