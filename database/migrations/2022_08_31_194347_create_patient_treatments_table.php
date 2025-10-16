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
        //        Schema::create('patient_treatments', function (Blueprint $table) {
        //            $table->id();
        //            $table->foreignId('patient_id')->constrained();
        //            $table->string('treatment_name');
        //            $table->string('treatment_cost');
        //            $table->enum('availability', ['easy_to_find_locally', 'hard_to_find_locally', 'not_available_locally']);
        //            $table->enum('schedule', ['daily', 'twice_a_week', 'weekly', 'monthly']);
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
        Schema::dropIfExists('patient_treatments');
    }
};
