<?php

use App\Models\Infos\Status;
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
        //        Schema::create('model_statuses', function (Blueprint $table) {
        //            $table->id();
        //            $table->morphs('statusable'); //teacher 1 teacher 1
        //            $table->foreignId('status_id')->constrained(); //2 3 status_id
        //            $table->timestamps();
        //        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_statuses');
    }
};
