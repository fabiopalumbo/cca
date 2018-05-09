<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_states',function(Blueprint $table){
            $table->increments('id');
            $table->integer('sale_id',false,true)->index();
            $table->integer('type_sale_state_id',false,true)->index();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->foreign("sale_id",'saleState_sale_foreign')->references('id')->on('sales');
            $table->foreign("type_sale_state_id",'saleState_typeSaleState_foreign')->references('id')->on('types_sales_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_states', function(Blueprint $table){
            $table->dropForeign('saleState_sale_foreign');
            $table->dropForeign('saleState_typeSaleState_foreign');
        });
        Schema::dropIfExists('sales_states');
    }

}
