<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TenantWarrantyToQuotationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('quotation_items', function(Blueprint $table) {
            $table->longText('warranty')->nullable()->after('charges');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('quotation_items', function(Blueprint $table) {
            $table->dropColumn('warranty');
        });
    }
}
