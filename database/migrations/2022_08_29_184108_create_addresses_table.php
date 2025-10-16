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
        //        Schema::create('addresses', function (Blueprint $table) {
        //            $table->id();
        //            $table->morphs('addressable');
        //            $table->string('label')->nullable();
        //            $table->string('country')->nullable();
        //            $table->string('city')->nullable();
        //            $table->string('state')->nullable();
        //            $table->string('line_1')->nullable();
        //            $table->string('line_2')->nullable();
        //            $table->string('floor')->nullable();
        //            $table->string('flat')->nullable();
        //            $table->string('lat')->nullable();
        //            $table->string('long')->nullable();
        //            $table->string('location_url')->nullable();
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
        Schema::dropIfExists('addresses');
    }
};
