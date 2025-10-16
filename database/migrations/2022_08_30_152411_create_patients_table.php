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
        //        Schema::create('patients', function (Blueprint $table) {
        //            $table->id();
        //            $table->foreignId('user_id')->constrained();
        //            $table->string('disease_name');
        //            $table->date('diagnosis_date')->nullable();
        //            $table->text('medical_report_url')->nullable();
        //            $table->string('medical_report_image')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
