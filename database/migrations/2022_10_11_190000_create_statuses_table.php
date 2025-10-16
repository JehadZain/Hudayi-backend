<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //        Schema::create('statuses', function (Blueprint $table) {
        //            $table->id();
        //            //$table->morphs('statusable');
        //            $table->string('name'); //pending
        //            //user 1 pending
        //            //user 2 pending
        //            //user 3 pending
        //
        //            //model_status morph user 1 status_id
        //            $table->foreignId('status_type_id')->constrained(); //academic, crm, call center FK
        //            //table status_types id name description
        //            $table->timestamps();
        //            $table->softDeletes();
        //        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
