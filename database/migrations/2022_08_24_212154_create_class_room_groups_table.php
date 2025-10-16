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
        //        Schema::create('class_room_groups', function (Blueprint $table) {
        //            $table->id();
        //            $table->foreignId('class_room_id')->constrained();
        //            $table->foreignId('grade_id')->constrained();
        //            $table->string('name');
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
        Schema::dropIfExists('class_room_groups');
    }
};
