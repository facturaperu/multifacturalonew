<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
           
            $table->increments('id');
            $table->date('date_of_issue');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('inventory_kardexable_id');
            $table->string('inventory_kardexable_type');
            $table->unsignedInteger('warehouse_id');
            $table->decimal('quantity', 12, 4);
            $table->timestamps(); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
        
    }
}
