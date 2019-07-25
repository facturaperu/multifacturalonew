<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToPaymentMethodTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_types', function (Blueprint $table) {
            //
        });

        DB::table('payment_method_types')->insert([
            ['id' => '05', 'description' => 'Cheque', 'has_card' => false], 
            ['id' => '06', 'description' => 'Detracccion', 'has_card' => false],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_types', function (Blueprint $table) {

        });        

        DB::table('payment_method_types')->where('id','06')->delete();
        DB::table('payment_method_types')->where('id','05')->delete();
        
    }
}
