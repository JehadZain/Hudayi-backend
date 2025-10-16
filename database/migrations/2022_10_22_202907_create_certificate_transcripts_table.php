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
        //        Schema::create('certificate_transcripts', function (Blueprint $table) {
        //            $table->id();
        //            $table->foreignId('certification_id')->constrained();
        //            $table->foreignId('subject_id')->constrained();
        //            $table->double('max');
        //            $table->double('points');
        //            $table->double('percentage');
        //            $table->string('grade_name')->nullable()->comment('could be A, B, Pass, Very good ..etc');
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
        Schema::dropIfExists('certificate_transcripts');
    }
};
