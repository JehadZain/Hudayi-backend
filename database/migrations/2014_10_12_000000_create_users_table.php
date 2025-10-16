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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique()->nullable();
            $table->string('identity_number')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('personal_image')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('status')->nullable();
            $table->uuid('qr_code')->unique()->nullable();
            $table->string('blood_type')->nullable();
            $table->text('note')->nullable();
            $table->string('current_address')->nullable();
            $table->string('is_has_disease')->nullable();
            $table->string('disease_name')->nullable();
            $table->string('is_has_treatment')->nullable();
            $table->string('treatment_name')->nullable();
            $table->string('are_there_disease_in_family')->nullable();
            $table->string('family_disease_note')->nullable();
            $table->foreignId('property_id')->nullable()->constrained();
            $table->string('image')->nullable();
            $table->string('is_approved')->default('false')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
