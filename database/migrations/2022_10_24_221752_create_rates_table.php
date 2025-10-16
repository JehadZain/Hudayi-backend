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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained();
            $table->foreignId('admin_id')->nullable()->constrained();
            $table->string('date')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('correct_reading_skill')->nullable();
            $table->string('teaching_skill')->nullable();
            $table->string('academic_skill')->nullable();
            $table->string('following_skill')->nullable();
            $table->string('plan_commitment')->nullable();
            $table->string('time_commitment')->nullable();
            $table->string('student_commitment')->nullable();
            $table->string('activity')->nullable();
            $table->string('commitment_to_administrative_instructions')->nullable();
            $table->string('exam_and_quizzes')->nullable();
            $table->string('score')->nullable();
            $table->string('percentage')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
