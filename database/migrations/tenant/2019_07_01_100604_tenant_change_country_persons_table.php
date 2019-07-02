<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeCountryPersonsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE persons MODIFY country_id CHAR(2) DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement("ALTER TABLE persons MODIFY country_id CHAR(2) NOT NULL");
    }
}
