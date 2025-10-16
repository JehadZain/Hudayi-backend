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
        //        Schema::create('contacts', function (Blueprint $table) {
        //            $table->id();
        //            $table->morphs('contactable');
        //            $table->string('email')->nullable();
        //            $table->string('phone')->nullable();
        //            $table->string('whatsapp')->nullable();
        //            $table->string('facebook')->nullable();
        //            $table->string('instagram')->nullable();
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
        Schema::dropIfExists('contacts');
    }
};
