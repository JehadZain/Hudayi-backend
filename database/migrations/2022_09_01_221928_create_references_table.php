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
        //        Schema::create('references', function (Blueprint $table) {
        //            $table->id();
        //            $table->morphs('referenceable');
        //            $table->string('referenced_by');
        //            $table->text('description');
        //            $table->string('jop_title');
        //            $table->text('letter_url');
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
        Schema::dropIfExists('references');
    }
};
